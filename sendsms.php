<?php

include_once('classes/sender.php');
include_once('classes/userprovider.php');

$sender = new Sender();
$userProvider = new User();

$user = $userProvider->getUser();
$user->validateBalance();

$sender->validateInput(); 
$sender->sendSms($user);

?>
