<?php

// Create the router
$router = new \Phalcon\Mvc\Router(false);

//Define a route
$router->add(
    "/:controller/:action/:params",
    array(
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    )
);

return $router;