<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

interface ErrorServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getErrorOutput(Error $error): array;
}
