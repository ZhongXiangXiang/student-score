<?php
    header('Content-type:text/html;charset=utf-8');
    /* 
        链接数据库  天龙八部 (通过php将数据从数据库中取出来，通过一个循环，以php和html混编形式将所有数据以表格形式在页面上输出)
    */
    //1.链接数据库
    /* 
        第一个参数：链接数据库的IP/域名（在这里链接的是本地的数据库，所以可直接传入localhost或本地IP）
        第二个参数：数据库的用户名 root
        第三个参数：密码
    */
    $link = mysql_connect('localhost', 'root', '123456');   //链接成功，返回值true

    //2.判断是否连接成功
    if(!$link){
        echo '链接失败';
        exit;   //exit代表终止后续代码
    }

    //3.设置要访问的字符集
    mysql_set_charset('uft8');

    //4.选择数据库
    mysql_select_db('yyy');

    //5.准备sql语句 获取yyy数据库中的所有的数据
    $sql = 'SELECT * FROM students';

    //6.发送sql语句
    $res = mysql_query($sql);  //发送完，可直接拿到其返回值，并在当前页面直接输出 。
    // var_dump($res);   //返回的是mysql结果，没办法对mysql结果直接操作，要把它变成我们想要的样子

    //==============================php和html混编=============================
    //设置表头
    echo "<table border = '1'>";
    echo "<tr><th>学生学号</th><th>学生姓名</th><th>英语成绩</th><th>数学成绩</th><th>语文成绩</th></tr>";

    //7.处理结果
    /* $row = mysql_fetch_assoc($res);
    $row = mysql_fetch_assoc($res);
    $row = mysql_fetch_assoc($res);   //调用几次就取出第几行的数据
    
    var_dump($row); */
    while($row = mysql_fetch_assoc($res)){
        // var_dump($row);
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['english']}</td><td>{$row['math']}</td><td>{$row['chinese']}</td></tr>";
    }
    echo "</table>";

    //8.关闭数据库
    mysql_close($link);

?>