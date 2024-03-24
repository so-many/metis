<?php

namespace SoManyProblems\Metis\Facades;

use Illuminate\Support\Facades\Facade;
use SoManyProblems\Metis\Drivers\RedisStore;

/**
 * @method static \Illuminate\Support\Collection all()
 * @method static \Illuminate\Support\Collection byLastXDay(int $dayCount)
 * @method static \Illuminate\Support\Collection today()
 *
 * @see \SoManyProblems\Metis\Drivers\RedisStore
 */
class Metis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RedisStore::class;
    }
}