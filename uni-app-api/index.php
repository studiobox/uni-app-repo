<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'lib/vendor/autoload.php';
require 'config/config.php';

$app = new \Slim\App([ 'settings' => $config ]);

// CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', "*")
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, x-www-form-urlencode')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

require_once 'routes/index.php';

$app->run();
// ->withHeader('Access-Control-Allow-Origin', 'http://portalapp.dezinersstudio.com')
?>