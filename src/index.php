<?php
$configFile = file_get_contents("Config/config.json");
$config = json_decode($configFile);

spl_autoload_register(function ($class) use ($config) {
    foreach ($config->autoloadFolder as $folder) {
        if (file_exists($folder . '/' . $class . '.php')) {
            require_once($folder . '/' . $class . '.php');
            break;
        }
    }
});

try {
    $httpRequest = new HttpRequest();
    $router = new Router();
    $httpRequest->setRoute($router->findRoute($httpRequest, $config->basepath));
    $httpRequest->run($config);
} catch (Exception $e) {
    $httpRequest = new HttpRequest("/Error", "GET");
    $router = new Router();
    $httpRequest->setRoute($router->findRoute($httpRequest, $config->basepath));
    $httpRequest->addParam($e);
    $httpRequest->run($config);
}