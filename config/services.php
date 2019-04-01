<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Event;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Loader;
use Phalcon\Logger;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\View\Simple;
use Phalcon\Security;

$di = new FactoryDefault();
$loader = new Loader();

$loader->registerNamespaces([
    'App\Models' => $config->application->modelsDir,
    'App\Controllers' => $config->application->controllersDir,
    'App\Library' => $config->application->libsDir,
    'App\Validations' => $config->application->validationsDir,
    'App\Tasks' => $config->application->tasksDir,
])->register();

/**
 * Config
 */
$di->setShared('config', $config);

/**
 * @return DbAdapter
 */
$di->setShared('setup', function () use ($config) {
    $connection = new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'port' => $config->database->port
    ]);

    if ($config->debug) {
        $eventsManager = new Phalcon\Events\Manager();
        $logger = new FileLogger($config->application->logsDir . "sql_debug.log");

        $eventsManager->attach('setup', function (Event $event, $connection) use ($logger) {
            if ($event->getType() == 'beforeQuery') {
                /** @var DbAdapter $connection */
                $logger->log($connection->getSQLStatement(), Logger::DEBUG);
            }
        });

        $connection->setEventsManager($eventsManager);
    }

    return $connection;
});

/**
 * Models manager
 */
$di->setShared('modelsManager', function () {
    return new Phalcon\Mvc\Model\Manager();
});

/**
 * Security
 */
$di->setShared('security', function () {
    $security = new Security();
    $security->setWorkFactor(12);
    return $security;
});

/**
 * Logger
 */
$di->setShared('logger', function () use ($config) {
    return new FileLogger($config->application->logsDir . "general.log");
});

// Set up the session service
$di->set('session', function() {
    $session = new Phalcon\Session\Adapter\Files();
    if (session_status() == PHP_SESSION_NONE) {
        $session->start();
    }
    return $session;
});

// Set up the flash session service
$di->set('flash', function () {
    return new FlashSession([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'debug'   => 'alert alert-dark',
        'warning' => 'alert alert-warning',
    ]);
});

// Register Volt as a service
$di->set('volt', function ($view, $di) use ($config) {
    $volt = new Volt($view, $di);
    $volt->setOptions([
        'compiledPath'      => function($template) use ($config) {
            return $config->application->cacheDir."templates/".md5($template).".php";
        },
        'compiledExtension' => '.php',
        'compileAlways' => true,
        'compiledSeparator' => '_',
    ]);
    $compiler = $volt->getCompiler();

    $compiler->addFunction('number_format', function ($resolvedArgs, $exprArgs) {
        return 'number_format(' . $resolvedArgs . ')';
    });

    $compiler->addFunction('pad', function ($resolvedArgs, $exprArgs) {
        return 'str_pad(' . $resolvedArgs . ', STR_PAD_LEFT)';
    });

    $compiler->addFunction('replace', function ($resolvedArgs, $exprArgs) {
        return 'str_replace(' . $resolvedArgs . ')';
    });

    $compiler->addFunction('float', function ($resolvedArgs, $exprArgs) use ($compiler) {
        $num = $compiler->expression($exprArgs[0]['expr']);
        $len = $compiler->expression($exprArgs[1]['expr']);
        return 'str_replace(" ", "&nbsp;", str_pad(number_format(' . $num . ', 2), '.$len.', " ", STR_PAD_LEFT))';
    });

    return $volt;
});

// Register Volt as template engine
$di->set('view', function() use ($config) {
    $view = new Simple();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => 'volt',
    ]);

    return $view;
});