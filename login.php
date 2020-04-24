<?php
    header('Content-type:text/html;charset=utf8');
    //统一返回格式（注册中会发生各种问题，注册不成功等，用统一格式返回）
    $responseData = array('code' => 0, 'message' => '');
    //取出传过来的数据
    $username = $_POST['username'];
    $password = $_POST['password'];

    //简单的验证
    if(!$username){ //用户名不存在 
        $responseData['code'] = 1;
        $responseData['message'] = '用户名不能为空';
        echo json_encode($responseData);
        exit;
    }
    if(!$password){
        $responseData['code'] = 2;
        $responseData['message'] = '密码不能为空';
        echo json_encode($responseData);
        exit;
    }

    //还要判断输入的用户名是否已存在
    //1.连接数据库
    $link = mysql_connect('localhost','root','123456');

    //2.判断是否连接成功
    if(!$link){
        $responseData['code'] = 3;
        $responseData['message'] = '数据库连接失败';
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    //3.设置字符集
    mysql_set_charset('utf8');

    //4.选择数据库
    mysql_select_db('yyy');

    //md5加密
    $str = md5(md5(md5($password)."xxx")."yyy"); 

    //5.登录
    $sql = "SELECT * FROM users WHERE username='{$username}' AND password='{$str}'";

    $res = mysql_query($sql);

    //取出第一行数据
    $row = mysql_fetch_assoc($res);
    if(!$row){   //不存在
        $responseData['code'] = 4;
        $responseData['message'] = '用户名或密码错误';
        echo json_encode($responseData);
    }else{
        $responseData['message'] = '登录成功';
        echo json_encode($responseData);
    }

    mysql_close($link);

?>