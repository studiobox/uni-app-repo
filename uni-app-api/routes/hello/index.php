<?php
$app->get('/hello/{name}', function ($request, $response, $args) {
    require 'config/config.php';
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
?>
