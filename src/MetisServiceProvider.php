<?php

namespace SoManyProblems\Metis;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SoManyProblems\Metis\Facades\Metis;

class MetisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Metis::class);
    }

    public function boot()
    {
        Event::listen(MessageLogged::class, function (MessageLogged $event) {
            Metis::addEntry($event->level, $event->message, $event->context);
        });
    }
}