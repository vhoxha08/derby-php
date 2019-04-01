<?php
/**
 * Created by PhpStorm.
 * User: vhoxha
 * Date: 3/30/19
 */

namespace App\Models;

/**
 * Class RaceHorse
 * @package App\Models
 * @property Horse $horse
 * @property Race $race
 */
class RaceHorse extends BaseModel
{
    /** @var string */
    public $hash;

    /** @var int */
    public $race_id;

    /** @var int */
    public $horse_id;

    /** @var float */
    public $endurance;

    /** @var float */
    public $position;

    /** @var float */
    public $timer;

    /** @var bool */
    public $race_finished;

    public function getSource(): string
    {
        return 'race_horses';
    }

    public function initialize()
    {
        $this->belongsTo(
            "horse_id",
            "App\Models\Horse",
            "id",
            ["alias" => "horse"]
        );

        $this->belongsTo(
            "race_id",
            "App\Models\Race",
            "id",
            ["alias" => "race"]
        );
    }

    /**
     * RaceHorse constructor.
     * @throws \Exception
     */
    public function onConstruct(): void
    {
        $this->hash = self::random_hash(8);
    }

    /**
     * @return float
     */
    public function runningSpeed(): float
    {
        if ($this->endurance > 0)
            return floatval($this->horse->getRealSpeed());
        else
            return floatval($this->horse->getRealSpeed() - 5.0 * (1.0 - ($this->horse->getStrength() * 8) / 100));
    }

    /**
     * @param float $time
     * @return float
     */
    public function distanceRun(float $time)
    {
        $d = $time * $this->runningSpeed();
        if ($this->endurance > 0) {
            $this->endurance = max($this->endurance - $d / 100, 0.0);
        }

        return floatval($d);
    }

    /**
     * @param float $endurance
     */
    public function setEndurance(float $endurance): void
    {
        $this->endurance = $endurance;
    }

    /**
     * @return RaceHorse
     * @throws \Exception
     */
    protected static function random(): RaceHorse
    {
        $sp = self::random_float(0, 10.0, 2);
        $st = self::random_float(0, 10.0, 2);
        $en = self::random_float(0, 10.0, 2);

        return new self(["speed" => $sp, "strength" => $st, "endurace" => $en]);
    }

    /**
     * @return Race|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getRace()
    {
        return $this->getRelated("race");
    }

    /**
     * @return Horse|\Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getHorse()
    {
        return $this->getRelated("horse");
    }

    /**
     * @param Race $race
     * @return void
     */
    public function setRace(Race $race): void
    {
        $this->race_id = $race->id;
    }

    /**
     * @param Horse $horse
     * @return void
     */
    public function setHorse(Horse $horse): void
    {
        $this->horse_id = $horse->id;
        $this->endurance = $horse->getEndurance();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $hash = $this->hash;

        $base = "RaceHorse {$hash}";
        $pos = str_pad(number_format($this->position, 2), 6, " ", STR_PAD_LEFT);
        $time = str_pad(number_format($this->timer, 2), 5, " ", STR_PAD_LEFT);
        $end = str_pad(number_format($this->endurance, 2), 4, " ", STR_PAD_LEFT);
        return "{$base} Pos: {$pos} m Time: {$time} s End: {$end}";
    }

    public static function getBestTime()
    {
        $horse = self::findFirst([
            'conditions' => 'race_finished = 1',
            'order' => 'timer ASC'
        ]);

        return $horse;
    }
}
