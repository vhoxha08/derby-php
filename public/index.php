<?php

use Phalcon\Logger;
use Phalcon\Mvc\Micro;

error_reporting(E_ALL);

include __DIR__ . "/../vendor/autoload.php";
include __DIR__ . "/../config/config.php";
include __DIR__ . "/../config/services.php";

$app = new Micro($di);
include __DIR__ . "/../config/routes.php";

$env = getenv('APPLICATION_ENV');

// add request logger
if ($env !== 'production') {
    $app->before(function () use ($app, $config) {
        /** @var \Phalcon\Http\Request $request */
        $request = $app->getService('request');
        $logger = new Phalcon\Logger\Adapter\File($config->application->logsDir . "requests.log");
        $logger->log('Request URL:' . $request->getURI(), Logger::DEBUG);
        if ($request->isPost() || $request->isPut()) {
            $rawBody = $request->getRawBody();
            $logger->log('Request Body: ' . $rawBody, Logger::DEBUG);
        }
    });
}

//handle invalid routes
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    $app->response->setContentType('application/json');
    $app->response->setJsonContent([
        'status' => 'error',
        'message' => "Page not found"
    ]);
    $app->response->send();
});

$app->error(function (Exception $exception) use ($app) {
    $app->response->setContentType('application/json');
    $app->response->setStatusCode(500, "Server Error")->sendHeaders();
    $app->response->setJsonContent([
        'status' => 'error',
        'message' => $exception->getMessage()
    ]);
    $app->logger->error($exception->getMessage()."\n".$exception->getTraceAsString());
    $app->response->send();
});
try {
    $app->handle();
} catch (Exception $e) {
    header('Content-type: text/html');
    echo '<pre>';
    echo $e->getMessage();
    echo $e->getTraceAsString()."\n";
    echo $e->getTraceAsString();
    echo '</pre>';
}
