<?php

declare(strict_types=1);

use Lukas\KoskampShoppingCart\Controllers\CartController;

require 'vendor/autoload.php';

session_start();

$action = $_GET['action'] ?? 'index';

$controller = new CartController();

switch ($action) {
    case 'add':
        $controller->add($_GET['objectNumber'] ?? null, $_GET['discount']);
        break;
    case 'remove':
        $controller->remove($_GET['objectNumber'] ?? null, $_GET['discount']);
        break;
    case 'update':
        $controller->updateAmount($_GET['objectNumber'] ?? null, $_GET['discount'], $_GET['amount']);
        break;
    case 'clear':
        $controller->clear();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
