<?php
require_once 'includes/config.php';
session_start();
unset($_SESSION['student_id']);
unset($_SESSION['student_name']);
unset($_SESSION['student_email']);
flash('success', 'You have been logged out successfully.');
redirect(SITE_URL . '/student-login.php');
?>
