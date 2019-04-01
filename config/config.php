<?php

use Phalcon\Config;
use Dotenv\Dotenv;

// we do not want to use .env files on production
if (getenv('APPLICATION_ENV') !== 'production') {
    $envFile = ((getenv('APPLICATION_ENV') === 'testing') ? '.env.test' : '.env');
    $dotEnv = Dotenv::create(dirname(__DIR__) . DIRECTORY_SEPARATOR, $envFile);
    $dotEnv->load();
}

$config = [
    'application' => [
        'modelsDir'         => __DIR__ . '/../app/models/',
        'controllersDir'    => __DIR__ . '/../app/controllers/',
        'libsDir'           => __DIR__ . '/../app/library/',
        'validationsDir'    => __DIR__ . '/../app/validations/',
        'tasksDir'          => __DIR__ . '/../app/tasks/',
        'logsDir'           => __DIR__ . '/../logs/',
        'viewsDir'          => __DIR__ . '/../templates/',
        'cacheDir'          => __DIR__ . '/../cache/',
        'maxRaces'          => 3
    ],

    'database' => [
        'adapter'           => 'Mysql',
        'host'              => getenv('DB_HOST'),
        'username'          => getenv('DB_USER'),
        'password'          => getenv('DB_PASSWORD'),
        'port'              => getenv('DB_PORT') ?: '3306',
        'dbname'            => getenv('DB_NAME')
    ],

    'debug' => getenv('DEBUG') === 'true',
];

$config = new Config($config);
