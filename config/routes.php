<?php

use App\Controllers\RaceController;

//race routes
$rc = new RaceController();
$app->get('/', [$rc, 'homeView']);
$app->get('/latest', [$rc, 'latestRaces']);
$app->post('/race', [$rc, 'createRace']);
$app->post('/race/progress', [$rc, 'progressRaces']);
$app->get('/race', [$rc, 'createRace']);
$app->get('/race/{id}', [$rc, 'getRace']);
$app->get('/race/{id}/tick', [$rc, 'raceTick']);
