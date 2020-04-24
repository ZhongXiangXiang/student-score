<?php
    header("Content-type:text/html;charset=utf-8");
    //连接数据库
    $link = mysql_connect('localhost','root','123456');

    //2.判断是否连接成功
    if(!$link){
        echo '数据库连接失败';
        exit;  //终止后续所有代码
    }

    //3.设置字符集
    mysql_set_charset('utf8');

    //4.选择数据库 
    mysql_select_db('yyy');

    //5.准备sql语句，将数据库中的所有数据全部取出来
    $sql = "SELECT * FROM users";

    //6.发送sql，发送回来后，可拿到我们查到mysql的结果。再通过while循环将数据逐个取出来
    $res = mysql_query($sql);

    $arr = array();   //希望前后端分离，将数据库中取出的每一行数据全部插在这个空数组里

    while($row = mysql_fetch_assoc($res)){
        array_push($arr, $row);
    }

    echo json_encode($arr);

    mysql_close($link);

?>