<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Error;

use PoP\ComponentModel\Error\Error;

interface ErrorManagerInterface
{
    public function convertFromCMSToPoPError(object $cmsError): Error;
    public function isCMSError(mixed $thing): bool;
}
