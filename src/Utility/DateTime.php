<?php

namespace App\Utility;

class DateTime
{

    public static function getToday()
    {
        return (new \DateTime())->format('Y-m-d');
    }

}