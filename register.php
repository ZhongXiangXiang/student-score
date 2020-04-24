<?php
    /* 
        先将注册的用户名、密码、时间拿到，在后台做一个简单的表单验证。连接数据库，准备一个sql语句去查询在数据库中有没有跟它重名的用户名，如果能够取出一行数据并存在，说明用户已存在，如果没有，进行md5加密，执行插入语句，最终返回布尔值，为true插入成功
    */
    header('Content-type:text/html;charset=utf-8');
    //统一返回格式（注册中会发生各种问题，注册不成功等，用统一格式返回）
    $responseData = array('code' => 0, 'message' => '');
    //取出传过来的数据
    $username = $_POST['username'];
    $password = $_POST['password'];
    $addTime = $_POST['addTime'];

    //简单的验证（不输用户名和密码也可以提交，这肯定是有问题的。所以对用户名和密码进行表单验证（后台验证），因为在前台会做一个完表单验证，验证用户名密码是否合法，但点击注册后，进行网络传输，可能会出现各种各样的问题，数据有可能传不到后台，或传到后台发现数据有问题，为了保险，后台再验证一遍，
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

    //5.准备sql  验证用户名是否重名
    $sql = "SELECT * FROM users WHERE username='{$username}'";

    //6.发送sql语句
    $res = mysql_query($sql);

    //7.取出返回mysql结果的一行数据
    $row = mysql_fetch_assoc($res);
    if($row){
        //用户名重名
        $responseData['code'] = 4;
        $responseData['message'] = '用户已存在';
        echo json_encode($responseData);
        exit;
    }

    //md5加密
    $str = md5(md5(md5($password)."xxx")."yyy");   //md5给$password加密，再拼一个xxx，再拼一个yyy。加密可加密任意层

    //如果有数据不重名，准备sql将数据插入到数据库中
    $sql2 = "INSERT INTO users(username,password,create_time) VALUES('{$username}','{$str}',{$addTime})";

    //将插入语句进行发送，返回布尔值是否发送成功
    $res = mysql_query($sql2);
    if(!$res){
        $responseData['code'] = 5;
        $responseData['message'] = '注册失败';
        echo json_encode($responseData);
    }else{
        $responseData['message'] = '注册成功';
        echo json_encode($responseData);
    }

    //8.关闭数据库
    mysql_close($link);
?>