<?php 
define('APP_ROOT', dirname(__DIR__));
chdir(APP_ROOT);
date_default_timezone_set('America/Sao_Paulo');

use Silex\Application;
use Workshop\Application\Db\Connection,
    Workshop\Application\Events\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$configCon = require 'config/database.php';

require "vendor/autoload.php";

$app = new Application();

$app['debug'] = true;

$app->get('/events', function(Request $request) use($app) {
	
	$fields = isset($_GET['fields'])? $_GET['fields'] : '';
	$events = new Events($app['connection']);
	
	return $app->json($events->getAll());

});

$app->get('/events/{id}', function($id) use($app) {
	
	$condition = array('id' => $id);
	$events = new Events($app['connection']);
	$events = $events->where($condition);

	return $app->json($events);
});

$app->post('/events', function(Request $request) use ($app) {
	
	$data = $request->request->all();
	
	$event = array(
		'title'      => (string) $data['title'],
		'content'    => (string) $data['content'],
		'created_at' => date('Y-m-d'),
		'updated_at' => date('Y-m-d')
	);

	$events = (new Events($app['connection']))->save($event);
	
	if(!$events) {
		return $app->json(['error' => 'Error']);
	}

	return $app->json(array('success' => 'Success'));
});

$app->put('/events', function(Request $request) use ($app) {
	
	$data = $request->request->all();

	$event = array(
		'id'         => $data['id'],
		'title'      => (string) $data['title'],
		'content'    => (string) $data['content'],
		'updated_at' => date('Y-m-d')
	);

	$events = (new Events($app['connection']))->save($event);
	
	if(!$events) {
		return $app->json(['error' => 'Error']);
	}

	return $app->json(array('success' => 'Success'));
});

$app->delete('/events/{id}', function($id) use ($app) {
	
	$events = (new Events($app['connection']))->delete($id);

	if(!$events) {
		return $app->json(array('error' => true));
	}

	return $app->json(array('success' => true));
	
});

$app['connection'] = $app->share(function($app) use($configCon) {
    return new Connection($configCon);
});

$app->run();
