<?php

namespace KissDev\Overseer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Overseer
 * @package KissDev\Overseer\Facades
 */
class Overseer extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'overseer';
    }
}