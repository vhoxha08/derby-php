<?php
/**
 * Created by PhpStorm.
 * User: vhoxha
 * Date: 3/30/19
 */

namespace App\Models;

//use Phalcon\Di;

/**
 * Class Race
 * @package App\Models
 * @property RaceHorse[] $horses
 */
class Race extends BaseModel
{
    public $id;

    /** @var string $hash */
    public $hash;

    /** @var float $length */
    public $length;

    /** @var float $timer */
    public $timer;

    /** @var \DateTime $started_on */
    public $started_on;

    /** @var \DateTime $timer */
    public $ended_on;

    public function initialize()
    {
        $this->hasMany("id", "App\\Models\\RaceHorse", "race_id", [
            "alias" => "horses",
            "order" => "position DESC"
        ]);
        $this->setSource('races');
    }

    /**
     * @return \Phalcon\Mvc\Model\ResultsetInterface|RaceHorse[]
     */
    public function getHorses()
    {
        return $this->getRelated("horses", ["order" => "position DESC"]);
    }

    /**
     * Race constructor.
     * @throws \Exception
     */
    public function onConstruct()
    {
        $this->hash = self::random_hash(8);
    }

    /**
     * @param float $time
     */
    public function tick($time = 10.0)
    {
        $this->timer += $time;

        if (is_null($this->started_on)) {
            $this->started_on = $this->currentDateTime();
        }

        foreach ($this->horses as $h) {
            if (!$h->race_finished) {
                $h->position = floatval(min($h->position + $h->distanceRun($time), $this->length));
                $h->timer += $time;

                if ($h->position >= $this->length) {
                    $h->race_finished = 1;
                } else {
                    $h->race_finished = 0;
                }

                $h->save();
            }
        }

        $rel = $this->getRelated('horses', [
            'conditions' => "race_finished = 0"
        ])->toArray();

        if (empty($rel)) {
            $this->ended_on = $this->currentDateTime();
        }

        $this->save();
    }

    /**
     * @param int $slots
     * @return Race|array
     * @throws \Exception
     */
    public static function generate($slots = 8)
    {
        $r = new Race();

        $r->save();

        $err = [];

        for ($i = 0; $i < $slots; $i++) {
            $rh = new RaceHorse();
            $h = Horse::spawn(1);
            $h->save();
            $rh->setRace($r);
            $rh->setHorse($h);
            $rh->save();

            $err[] = $rh->getMessages();
        }
        return [$r, $err];
    }

    public function __toString()
    {
        $str = "Timer: {$this->timer}\n";

        foreach ($this->getHorses() as $h) {
            $str .= $h . "\n";
        }

        return $str;
    }

    public function finished()
    {
        return !is_null($this->ended_on);
    }
}
