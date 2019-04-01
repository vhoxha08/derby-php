<?php

namespace App\Models;

use App\Library\Utils;
use \Exception;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData\Memory;

/**
 * Class BaseModel
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
abstract class BaseModel extends Model
{
    use Utils;

    /** @var string */
    private static $lastErrorMessage;

    /**
     * Set created_at before validation
     */
    public function beforeValidationOnCreate()
    {
        $this->created_at = $this->currentDateTime();
        $this->updated_at = $this->currentDateTime();
    }

    /**
     * Set created_at before validation
     */
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = $this->currentDateTime();
    }

    /**
     * Set updated_at on save
     */
    public function beforeSave()
    {
        $this->updated_at = $this->currentDateTime();
    }

    /**
     * @param $min
     * @param $max
     * @param int $rounded
     * @return float|int
     * @throws Exception
     */
    public static function random_float($min, $max, $rounded = 1)
    {
        return round(random_int($min, $max - 1) + random_int(0, 1000 - 1) / 1000, $rounded);
    }

    /**
     * @param int $len
     * @return string
     * @throws Exception
     */
    public static function random_hash($len = 16)
    {
        return bin2hex(random_bytes($len / 2));
    }

    /**
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $called_class = get_called_class();
        /** @var Model $model */
        $model = new $called_class();
        self::fill($data, $model);
        return ($model->save()) ? $model : false;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        self::fill($data, $this);
        return ($this->update()) ? $this : false;
    }

    /**
     * @param array $data
     * @param Model $model
     */
    public static function fill($data, $model)
    {
        $modelAttributes = (new Memory())->getAttributes($model);
        foreach ($data as $key => $value) {
            if (in_array($key, $modelAttributes)) {
                $model->{$key} = $value;
            }
        }
    }
}
