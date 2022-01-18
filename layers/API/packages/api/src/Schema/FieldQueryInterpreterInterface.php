<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface as UpstreamFieldQueryInterpreterInterface;

interface FieldQueryInterpreterInterface extends UpstreamFieldQueryInterpreterInterface
{
    public function extractFieldArgumentValues(string $field): array;
    public function extractFieldOrDirectiveArgumentValues(string $fieldOrDirectiveArgsStr): array;
}
