<?php 
define('APP_ROOT', dirname(__DIR__));
chdir(APP_ROOT);

use Silex\Application;

require "vendor/autoload.php";

$app = new Application();

$app->get('/', function() use($app) {
	return 'Hello World';
});
$app->get('/users/{name}', function($name) use($app) {
	return 'Welcome ' . $name;
});

$app->run();