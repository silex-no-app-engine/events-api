<?php 
define('APP_ROOT', dirname(__DIR__));
chdir(APP_ROOT);

use Silex\Application;
use Workshop\Application\Db\Connection,
    Workshop\Application\Events\Events;

$configCon = require 'config/database.php';
require "vendor/autoload.php";

$app = new Application();

$app['debug'] = true;

$app->get('/events', function() use($app) {

	$events = new Events($app['connection']);

	return $app->json($events->getAll());

});
$app->get('/events/{id}', function($id) use($app) {
	return 'Welcome ' . $id;
});

$app['connection'] = $app->share(function($app) use($configCon) {
    return new Connection($configCon);
});

$app->run();