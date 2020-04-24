<?php
    /* 
        
    */
    header('Content-type:text/html;charset=utf-8');
    //统一返回格式（注册中会发生各种问题，注册不成功等，用统一格式返回）
    $responseData = array('code' => 0, 'message' => '');
    //取出传过来的数据
    $username = $_POST['username'];
    $password = $_POST['password'];
    $id = $_POST['id'];

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
    if(!$id){ 
        $responseData['code'] = 3;
        $responseData['message'] = '修改的用户不存在';
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

    //5.判断用户名是否重名  用户表里的用户名和我相同，但是id和我当前提交的id不一样，也就是说，除了当前这条数据以外，用户表里还有另一条数据用户名和我相同，说明用户名重名（可能跳到修改页面，没修改直接提交，用户名肯定是存在，所以加一个AND id!{$id}）
    $sql = "SELECT * FROM users WHERE username='{$username}' AND id!={$id}";

    $res = mysql_query($sql);  //发送
    $row = mysql_fetch_assoc($res);  //查询，要将第一行数据取出来，看是否存在。
    if($row){
        $responseData['code'] = 4;
        $responseData['message'] = '用户名重名，不能修改';
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    $sql1 = "SELECT * FROM users WHERE username='{$username}' AND id={$id} AND password='{$password}'";  //未修改，修数据中和提交过来的密码都是加密过了的
    $res1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($res1);
    if($row1){
        $responseData['message'] = '未修改数据内容';
        echo json_encode($responseData);
        exit;
    }

    //修改了数据。无论密码是否更改，都对密码再次或重新加密
    //md5加密
    $str = md5(md5(md5($password)."xxx")."yyy");

    $sql2 = "UPDATE users SET username='{$username}',password='{$str}' WHERE id={$id}";

    $res2 = mysql_query($sql2);  
    if($res2){       //修改，返回ture(修改成功)或false
        $responseData['message'] = '修改成功';
        echo json_encode($responseData);
    }else{
        $responseData['code'] = 5;
        $responseData['message'] = '用户名修改失败，请重试';
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    //8.关闭数据库
    mysql_close($link);
?>