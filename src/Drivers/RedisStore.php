<?php

namespace SoManyProblems\Metis\Drivers;

use SoManyProblems\Metis\Contracts\Metis;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;
use SoManyProblems\Metis\LogItem;
use Carbon\Carbon;

class RedisStore implements Metis
{
    public function all()
    {
        $keys = $this->stripRedisPrefix(collect(Redis::keys('metis:*')));
        return $this->getUsingKeys($keys);
    }

    public function today()
    {
        $key = $this->generateKey(now());
        return $this->getUsingKeys(collect([$key]));
    }

    public function byLastXDay(int $dayCount)
    {
        $weekDays = [];
        for($i=0; $i < $dayCount; $i++)
        {
            $weekDays []= now()->subDay($i);
        }

        $keys = collect($weekDays)->map(fn($timestamp) => $this->generateKey($timestamp));

        return $this->getUsingKeys($keys);
    }

    public function addEntry(string $level, string $message, array $context)
    {
        $timestamp = now();
            
        $logItem = new LogItem(
            $level, 
            $message, 
            $context, 
            $timestamp,
        );

        $logEntry = serialize($logItem);
        
        $key = $this->generateKey(now());
        Redis::rpush($key, $logEntry);
    }

    private function generateKey(Carbon $timestamp)
    {
        $year = $timestamp->year;

        $currentLocale = Carbon::getLocale();
        Carbon::setLocale('en');
        $monthName = $timestamp->monthName;
        Carbon::setLocale($currentLocale);

        $day = $timestamp->day;

        $key = $day . "." . $monthName . "." . $year;
        
        return 'metis:' . $key;
    }

    private function stripRedisPrefix(Collection $keys)
    {
        $prefix = config('database.redis.options.prefix', '');

        return $keys->map(function($key) use($prefix) {
            return str($key)->substr(strlen($prefix));
        });
    }

    private function getUsingKeys(Collection $keys)
    {
        return $keys
            ->map(fn($key) => Redis::lrange($key, 0, -1))
            ->flatten()
            ->map(fn($entry) => unserialize($entry))
            ->sortByDesc('timestamp');
    }
}