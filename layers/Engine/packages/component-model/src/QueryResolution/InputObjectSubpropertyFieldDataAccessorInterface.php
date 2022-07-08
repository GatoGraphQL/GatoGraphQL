<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

interface InputObjectSubpropertyFieldDataAccessorInterface extends FieldDataAccessorInterface
{
    public function getInputObjectSubpropertyName(): string;
}
