<?php

require 'nikeah.php';

define('INCLUDE', True);
$request = new Request();
$web = new DottedWeb();

$web->setGET(array(
    '/' => 'home',
    '/login' => 'login_page',
    '/hello/:name' => 'greet',
    '/:controller/:action/:value' => 'test3'
));

$web->setPOST(array(
    '/login' => 'post_receiver'
));

function home() {
    echo('Home sweet home');
}

function login_page() {
    @include 'form.php';
}

function greet($name) {
    echo "Hello, $name!";
}

function test3($v1, $v2, $v3) {
    echo("v1=$v1, v2=$v2, v3=$v3");
}

function post_receiver($post) {
    var_dump($post);
}

$web->run($request->method, $request->relative_path);

?>
