<?php

session_start();
require_once "./config.php";
error_reporting(!$config['debug'] ? 0 : E_ALL);
//
//ini_set('display_errors',  1);
//ini_set('display_startup_errors',  1);
//error_reporting(E_ALL);

require_once "../core/helpers/core.php";
require_once "../core/controllers/CoreController.php";

require_once '../core/vendor/autoload.php';

$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer($config['layout_path']);
$container['config'] = $config;

Route::SetApp($app);

Route::Get("/", "Home", "Home", 1);
Route::Get("/api/unauthorized", "Core", "Core", 1);

require_once "./routes.php";

$app->run();