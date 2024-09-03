<?php 

require_once 'config/config.php';

unset($_SESSION['user_id']);
unset($_SESSION['user_cliente']);
unset($_SESSION['user_name']);
unset($_SESSION['user_type']);

session_destroy();

header('Location: index.php');