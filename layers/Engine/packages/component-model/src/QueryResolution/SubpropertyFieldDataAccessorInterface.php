<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

interface SubpropertyFieldDataAccessorInterface extends FieldDataAccessorInterface
{
    public function getSubpropertyName(): string;
}
