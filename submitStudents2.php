<?php
    /* 
        
    */
    header('Content-type:text/html;charset=utf-8');
    //统一返回格式（注册中会发生各种问题，注册不成功等，用统一格式返回）
    $responseData = array('code' => 0, 'message' => '');
    //取出传过来的数据
    $name = $_POST['name'];
    $english = $_POST['english'];
    $math = $_POST['math'];
    $chinese = $_POST['chinese'];
    $id = $_POST['id'];

    //简单的验证 
    if(!$name || !$english || !$math || !$chinese){ //用户名不存在
        $responseData['code'] = 1;
        $responseData['message'] = '学员姓名或英语/数学/语文成绩不能为空';
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
    $sql = "SELECT * FROM students WHERE name='{$name}' AND id!={$id}";

    $res = mysql_query($sql);  //发送
    $row = mysql_fetch_assoc($res);  //查询，要将第一行数据取出来，看是否存在。
    if($row){
        $responseData['code'] = 4;
        $responseData['message'] = '修改的学员姓名重名，不能修改';
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    $sql1 = "SELECT * FROM students WHERE name='{$name}' AND id={$id} AND english={$english} AND math={$math} AND chinese={$chinese}";  //未修改，
    $res1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($res1);
    if($row1){
        $responseData['message'] = '未修改数据内容，跳回学员成绩列表';
        echo json_encode($responseData);
        exit;
    }

    //修改了数据。
    $sql2 = "UPDATE students SET name='{$name}',english={$english},math={$math},chinese={$chinese} WHERE id={$id}";

    $res2 = mysql_query($sql2);  
    if($res2){       //修改，返回ture(修改成功)或false
        $responseData['message'] = '修改成功';
        echo json_encode($responseData);
    }else{
        $responseData['code'] = 5;
        $responseData['message'] = '数据修改失败，请重试';
        echo json_encode($responseData);
        exit; //终止后续所有的代码
    }

    //8.关闭数据库
    mysql_close($link);
?>