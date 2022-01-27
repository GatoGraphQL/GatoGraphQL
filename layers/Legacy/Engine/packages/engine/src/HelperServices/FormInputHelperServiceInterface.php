<?php

declare(strict_types=1);

namespace PoP\Engine\HelperServices;

interface FormInputHelperServiceInterface
{
    public function getMultipleInputName(string $name, string $subname): string;
}
