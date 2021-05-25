<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

interface ApplicationStateHelperServiceInterface
{
    public function doingJSON(): bool;
}
