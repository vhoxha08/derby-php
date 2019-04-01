<?php
/**
 * Created by PhpStorm.
 * User: Visar Hoxha <visar.hoxha@deltaresearch.eu>
 * Date: 14/09/2018
 */

namespace App\Controllers;

use App\Models\Race;
use App\Models\RaceHorse;

/**
 * Class HomeController
 * @package App\Controllers
 */
class RaceController extends BaseController
{
    /**
     * @return string
     */
    public function homeView()
    {
        $races = Race::find([
            "order" => "created_at DESC",
            "conditions" => "ended_on IS NULL"
        ]);

        $best = RaceHorse::getBestTime();

        $this->logger->warning(var_export($best, true));

        return $this->view->render('race/home', [
            "title" => "Race Simulator",
            "races" => $races,
            "best"  => $best
        ]);
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     * @throws \Exception
     */
    public function createRace()
    {
        $raceCount = Race::count('ended_on IS NULL');

        $this->logger->error(var_export($raceCount, true));

        $max = $this->config->get('application')->maxRaces;
        if ($raceCount < $max) {
            Race::generate();
            $this->flash->success("Race was created");
        } else {
            $this->flash->error("No more than $max open races allowed");
        }
        return $this->response->redirect('/');
    }

    /**
     * @param $id
     * @return \Phalcon\Http\ResponseInterface
     */
    public function raceTick($id)
    {
        /** @var Race $race */
        $race = Race::findFirst($id);
        $race->tick();
        $this->session->set('last_tick', $race->id);

        return $this->response->redirect($this->request->getHTTPReferer());
    }

    /**
     * @param $id
     * @return string
     */
    public function getRace($id)
    {
        /** @var Race $race */
        $race = Race::findFirst($id);

        return $this->view->render('race/view', [
            "title" => "Race {$race->hash}",
            "race" => $race,
        ]);
    }

    /**
     * Progress all races
     * @return \Phalcon\Http\ResponseInterface
     */
    public function progressRaces()
    {
        /** @var Race[] $races */
        $races = Race::find('ended_on IS NULL');

        foreach ($races as $race) {
            $race->tick();
        }

        return $this->response->redirect($this->request->getHTTPReferer());
    }

    /**
     * Get last 5 races
     * @return string
     */
    public function latestRaces()
    {
        $races = Race::find([
            "order" => "created_at DESC",
            "limit" => 5
        ]);
        return $this->view->render('race/latest', [
            "title" => "Race Simulator",
            "races" => $races,
        ]);
    }
}