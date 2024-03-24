<?php

namespace SoManyProblems\Metis;

use Carbon\Carbon;

class LogItem
{
    public function __construct(
        public string $level,
        public string $message,
        public array $context,
        public Carbon $timestamp,
    ) {}
}