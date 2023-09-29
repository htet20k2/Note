<?php
$db = new PDO("mysql:host=localhost;dbname=note",'root','');
if(!$db){
    echo "fail";
}
?>
