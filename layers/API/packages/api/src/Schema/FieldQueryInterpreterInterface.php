<?php

declare(strict_types=1);

namespace PoP\API\Schema;

interface FieldQueryInterpreterInterface extends \PoP\ComponentModel\Schema\FieldQueryInterpreterInterface
{
    public function extractFieldArgumentValues(string $field): array;
    public function extractDirectiveArgumentValues(string $field): array;
}
