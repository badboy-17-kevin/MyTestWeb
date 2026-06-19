<?php
 //销毁会话
session_start();
 session_unset();//清除所有session的变量
session_destroy();//销毁session
 header("Location:index.php");
 exit;