<?php
require_once __DIR__ . '/includes/auth.php';

logout_user();
session_name(SESSION_NAME);
session_start();
flash_set('info', 'You have been signed out.');
redirect(APP_URL . '/login.php');