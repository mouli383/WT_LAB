<?php
require_once '../config/connection.php';

/**
 * LibManage Elite SaaS - Direct Entry Protocol
 * Bypass marketing layer and enter orbital session
 */
if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
