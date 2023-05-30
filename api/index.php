<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Collection\Manager;

define("BASE_PATH", __DIR__);

require_once(BASE_PATH . '/vendor/autoload.php');

// Use Loader() to autoload our model
$container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://deekshapandey:Deeksha123@cluster0.whrrrpj.mongodb.net/?retryWrites=true&w=majority'
        );

        return $mongo->testing_new;
    },
    true
);
$container->set(
    'collectionManager',
    function () {
        return new Manager();
    }
);
$app = new Micro($container);
// Define the routes here

// Retrieves all movies
$app->post(
    '/api/signup',
    function () {
        $payload = [
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
        ];
        $collection = $this->mongo->Users;
        $status = $collection->insertOne($payload);
        print_r($status);
    }
);

// Searches for movies with $name in their name
$app->post(
    '/api/login',
    function () {
        $collection = $this->mongo->Users;
        $m = $collection->findOne(["email" => $_POST['email'], "password" => $_POST['password']]);
        return json_encode($m);
    }
);


$app->post(
    '/api/authorize',
    function () {
        $collection = $this->mongo->Users;
        $m = $collection->findOne(
            [
                "name" => $_POST['name'], "email" => $_POST['email'], "password" => $_POST['password']
            ]
        );

        print_r($m);
    }
);


$app->handle($_SERVER['REQUEST_URI']);
