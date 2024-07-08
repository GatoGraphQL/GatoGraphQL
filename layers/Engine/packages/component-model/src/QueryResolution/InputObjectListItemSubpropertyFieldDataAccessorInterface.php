<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

interface InputObjectListItemSubpropertyFieldDataAccessorInterface extends FieldDataAccessorInterface
{
    public function getInputObjectListSubpropertyName(): string;
    public function getInputObjectListItemPosition(): int;
}
