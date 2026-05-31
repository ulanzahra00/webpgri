<?php
// Logout menghancurkan session admin.
require_once __DIR__ . '/../includes/functions.php';
session_destroy();
redirect('admin/login.php');
