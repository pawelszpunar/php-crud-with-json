<?php
require_once 'helper.php'; 
$jh = new Helper();

echo "<h1 class='starting-title'>Users list &#128075;</h1>";

if(isset($_POST['adduser'])) {
    $jh->addUser($_POST);
}

if(isset($_POST['id'])) {
    $jh->deleteRowById($_POST['id']);
}

$file_content = $jh->getData();

echo $jh->printTable($file_content);

echo $jh->addForm();

?>









