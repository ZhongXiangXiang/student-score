<?php
    header('Content-type:text/html;charset=utf8');
    //统一返回格式
    $responseData = array('code'=>0,'message'=>'');
    // var_dump($_POST);  //刚刚是通过post提交过来的，所以数据放在当前php页面$_POST的全局数组里
    $name = $_POST['name'];  //从关联数组中将数据一个一个取出来。不能通过浏览器直接加载这个php页面，因为未进行post提交，取不出这些数据，会报错。首先要在html中提交数据，才能提交成功
    $english = $_POST['english'];
    $math = $_POST['math'];
    $chinese = $_POST['chinese'];


    //接着通过天龙八部8个步骤连接数据库，最终将数据全部插入数据库中
    //1.连接数据库
    $link = mysql_connect('localhost', 'root', '123456');

    //2.判断是否连接成功
    if(!$link){
        $responseData['code'] =1;
        $responseData['message'] = '数据库连接失败';
        //返回到前台页面
        echo json_encode($responseData); //将关联数组转换成json格式字符串统一返回
        exit;
    }

    //3.设置字符集
    mysql_set_charset('uft8');

    //4.选择数据库
    mysql_select_db('yyy');

    $sql2 = "SELECT * FROM students WHERE name='{$name}'";
    $res2 = mysql_query($sql2);
    $row2 = mysql_fetch_assoc($res2);
    if($row2){
        $responseData['code'] = 3;
        $responseData['message'] = '该学员姓名已存在';
        echo json_encode($responseData);
        exit;
    }

    //5.准备sql语句进行插入操作
    $sql = "INSERT INTO students(name,english,math,chinese) VALUES('{$name}',{$english},{$math},{$chinese})";  //因为没有id，不是全字段，所以要写相应字段，这里最好不要空格，VALUES中字符串和日期放在单引号里
    // echo $sql;

    //6.发送sql语句
    $res = mysql_query($sql);

    if(!$res){  //如果返回false，插入失败
        $responseData['code'] =2;
        $responseData['message'] = '添加学员成绩失败';
        //返回到前台页面
        echo json_encode($responseData); //将关联数组转换成json格式字符串统一返回
        exit;
    }else{
        $responseData['message'] = '添加学员成绩成功';  //插入成功，code不用改，保持原来的0
        //返回到前台页面
        echo json_encode($responseData); //将关联数组转换成json格式字符串统一返回
    }

    
    //8.关闭数据库
    mysql_close($link);
?>