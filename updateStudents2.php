<?php
    //获取要修改的数据
    //php页面通过id取出数据库中对应的值，并加载到页面上
    header('Content-type:text/html;charset=utf8');
    //统一返回格式（注册中会发生各种问题，注册不成功等，用统一格式返回）
    $responseData = array('code' => 0, 'message' => '');

    $id = $_GET['id'];

    //判断id是否存在
    if(!$id){    //id不存在
        $responseData['code'] = 1;
        $responseData['message'] = '没有要修改的数据';
        //返回到前台页面
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    //连接数据库
    $link = mysql_connect('localhost','root','123456');

    //2.判断是否连接成功
    if(!$link){
        $responseData['code'] = 2;
        $responseData['message'] = '数据库连接失败';
        //返回到前台页面
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    //3.设置字符集
    mysql_set_charset('utf8');

    //4.选择数据库
    mysql_select_db('yyy');

    //5.准备sql查找数据
    $sql = "SELECT * FROM students WHERE id={$id}";

    //6.发送sql语句，查询语句，返回的是mysql result的mysql结果
    $res = mysql_query($sql);

    $row = mysql_fetch_assoc($res);  //取出一行结果

    if(!$row){
        $responseData['code'] = 3;
        $responseData['message'] = '修改的数据不存在';
        //返回到前台页面
        echo json_encode($responseData);
    }else{
        $responseData['message'] = json_encode($row);  //转为json格式字符串
        //返回到前台页面
        echo json_encode($responseData);
    }

    mysql_close($link);
?>