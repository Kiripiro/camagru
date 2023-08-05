<?php
//phpinfo();

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
    if ($e instanceof NoRouteFoundException) {
        $httpRequest = new HttpRequest("/notfound", "GET");
        $router = new Router();
        $httpRequest->setRoute($router->findRoute($httpRequest, $config->basepath));
        $httpRequest->addParam($e);
        $httpRequest->run($config);
    } else {
        $session = new Session();
        var_dump($session->get("error_page"));
        var_dump($session->get("error_param"));
        if (!$session->get("error_page")) {
            $session->set("error_page", "/notfound");
        }
        if (!$session->get("error_message")) {
            $session->set("error_message", "An error has occured.");
        }
        if ($session->get("error_param")) {
            var_dump("test");
            $httpRequest = new HttpRequest($session->get("error_page") . $session->get("error_param"), "GET");
        } else
            $httpRequest = new HttpRequest($session->get("error_page"), "GET");
        var_dump($httpRequest);
        $router = new Router();
        $httpRequest->setRoute($router->findRoute($httpRequest, $config->basepath));
        $httpRequest->addParam($e);
        $httpRequest->run($config);
    }
}