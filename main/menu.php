<?php
$page = $_GET['page'] ?? 'dashboard'; // nếu không có thì mặc định dashboard

switch ($page) {
    case 'indexExpense':
        include 'modules/indexExpense.php';
        break;
    case 'addExpense':
        include 'modules/addExpense.php';
        break;
    case 'editExpense':
        include 'modules/editExpense.php';
        break;
    case 'profile':
        include 'modules/profile.php';
        break;
    case 'statistics':
        include 'modules/statistics.php';
        break;
    default:
        include 'main/dashboard.php'; // mặc định load Dashboard
        break;
}
