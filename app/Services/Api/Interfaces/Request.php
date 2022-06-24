<?php

namespace App\Services\Api\Interfaces;

interface Request
{
    public function getUrl(): string;

    public function call(): self;

    public function getResponseBody():string;
}
