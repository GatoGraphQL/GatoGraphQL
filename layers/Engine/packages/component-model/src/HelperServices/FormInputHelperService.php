<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

class FormInputHelperService implements FormInputHelperServiceInterface
{
    public function getMultipleInputName(string $name, string $subname): string
    {
        // Use "_" instead of "-" so it's compatible with GraphQL
        // Then, a query field will be called "date_from" (allowed) instead of "date-from" (forbidden)
        // Otherwise it throws error: "Error: Names must match /^[_a-zA-Z][_a-zA-Z0-9]*$/ but "date-from" does not."
        return $name . '_' . $subname;
    }
}
