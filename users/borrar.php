<?php 
include("function.php");
$id = $_GET['id'];
delete('users','id',$id);
header("location:index.php");
?>