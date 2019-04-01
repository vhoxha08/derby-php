<?php


namespace App\Models;


use Exception;

class Horse extends BaseModel
{
    /** @var int $id */
    public $id;

    /** @var string $hash */
    protected $hash;

    /** @var float $speed */
    private $speed;

    /** @var float $strength */
    private $strength;

    /** @var float $endurance */
    private $endurance;

    public function getSource()
    {
        return 'horses';
    }

    public function initialize()
    {
        $this->belongsTo('horse_id', "App\Models\RaceHorse", "id");
    }

    /**
     * Horse constructor.
     * @throws Exception
     */
    public function onConstruct()
    {
        $this->hash = self::random_hash(8);
    }

    /**
     * Prints the object
     * @return string
     */
    public function __toString()
    {
        $hash = $this->hash;
        $speed = number_format($this->speed, 2);
        $real_speed = str_pad(number_format($this->getRealSpeed(), 2), 5, ' ', STR_PAD_LEFT);
        $strength = number_format($this->strength, 2);
        $endurance = number_format($this->endurance, 2);
        return "Horse {$hash} -> Sp: {$speed} ({$real_speed}) m/s Str: {$strength} End: {$endurance}";
    }

    /**
     * @return float
     */
    public function getSpeed(): float
    {
        return $this->speed;
    }

    /**
     * @return float
     */
    public function getRealSpeed(): float
    {
        return $this->speed + 5.0;
    }

    /**
     * @param float $speed
     */
    public function setSpeed(float $speed): void
    {
        if ($speed >= 0.0 && $speed<= 10.0)
            $this->speed = $speed;
        else
            throw new \OutOfRangeException("Speed should be a value between 0.0 and 10.0");
    }

    /**
     * @return float
     */
    public function getStrength(): float
    {
        return $this->strength;
    }

    /**
     * @param float $strength
     */
    public function setStrength(float $strength): void
    {
        if ($strength >= 0.0 && $strength<= 10.0)
            $this->strength = $strength;
        else
            throw new \OutOfRangeException("Strength should be a value between 0.0 and 10.0");
    }

    /**
     * @return float
     */
    public function getEndurance(): float
    {
        return $this->endurance;
    }

    /**
     * @param float $endurance
     */
    public function setEndurance(float $endurance): void
    {
        if ($endurance >= 0.0 && $endurance<= 10.0)
            $this->endurance = $endurance;
        else
            throw new \OutOfRangeException("Endurance should be a value between 0.0 and 10.0");
    }

    /**
     * @return float
     */
    public function runningSpeed()
    {
        if ($this->endurance > 0)
            return floatval($this->getRealSpeed());
        else
            return floatval($this->getRealSpeed() - 5.0 * (1.0 - ($this->getStrength() * 8) / 100));
    }

    /**
     * Generate a Horse or a collection of Horses with random stats
     * @param int $num
     * @return Horse|Horse[]
     * @throws Exception
     */
    public static function spawn(int $num = 1)
    {
        if ($num == 1) {
            return self::random();
        } else {
            $horses = [];
            for ($i = 0; $i < $num; $i++) {
                $horses[] = self::random();
            }

            return $horses;
        }
    }

    /**
     * @return Horse
     */
    protected static function random()
    {
        $sp = self::random_float(0, 10.0, 2);
        $st = self::random_float(0, 10.0, 2);
        $en = self::random_float(0, 10.0, 2);

        return new self(["speed" => $sp, "strength" => $st, "endurance" => $en]);
    }
}
