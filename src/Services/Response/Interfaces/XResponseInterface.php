<?php

namespace JOOservices\XClient\Services\Response\Interfaces;

interface XResponseInterface
{
    public function isSuccessful(): bool;

    public function getRawBody(): string;
}
