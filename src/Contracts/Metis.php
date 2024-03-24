<?php

namespace SoManyProblems\Metis\Contracts;

interface Metis
{
    public function all();

    public function addEntry(string $level, string $message, array $context);
}