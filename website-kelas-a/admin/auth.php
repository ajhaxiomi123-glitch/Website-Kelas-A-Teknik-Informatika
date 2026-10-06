<?php
require_once '../config.php';

if (!is_admin()) {
    redirect('login.php');
}
?>