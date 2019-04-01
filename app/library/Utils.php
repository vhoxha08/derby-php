<?php

namespace App\Library;

/**
 * Class Util
 * @package App\Library
 */
trait Utils
{
    /**
     * @return bool|string
     */
    public function currentDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @return bool|string
     */
    public function currentDate()
    {
        return date('Y-m-d');
    }

    /**
     * @param \DateTime $dateTime
     * @param string $format
     * @return string
     */
    public function formatDateTime(\DateTime $dateTime, $format = 'Y-m-d H:i:s')
    {
        return $dateTime->format($format);
    }
}
