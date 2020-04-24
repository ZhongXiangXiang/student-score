<?php
    //删除操作
    header('Content-type:text/html;charset=utf-8');
    //统一返回格式
    $responseData = array('code' => 0, 'message' => '');

    //拿到要删除的数据
    $id = $_GET['id'];

    //1.连接数据库
    $link = mysql_connect('localhost','root','123456');

    //2.判断是否连接成功
    if(!$link){
        $responseData['code'] = 1;
        $responseData['message'] = '数据库连接失败';
        //返回到前台页面
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    //3.设置字符集
    mysql_set_charset('utf8');

    //4.选择数据库
    mysql_select_db('yyy');

    //5.准备sql语句，通过id删除
    $sql = "DELETE FROM students WHERE id={$id}";

    //6.发送sql语句，删除操作和插入操作一样，会返回一个布尔值，为true，删除成功
    $res = mysql_query($sql);  

    if(!$res){
        $responseData['code'] = 2;
        $responseData['message'] = '删除用户数据失败';
        //返回到前台页面
        echo json_encode($responseData);
    }else{
        $responseData['message'] = '删除用户数据成功';
        //返回到前台页面
        echo json_encode($responseData);
    }

    mysql_close($link);


?>