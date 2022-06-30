<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use stdClass;

abstract class AbstractSubpropertyInputObjectFieldArgumentMutationDataProvider extends AbstractInputObjectFieldArgumentMutationDataProvider
{
    protected function getInputObjectValue(): stdClass
    {
        $inputObjectValue = parent::getInputObjectValue();
        $subpropertyName = $this->getSubpropertyName();
        return $inputObjectValue->$subpropertyName;
    }

    abstract protected function getSubpropertyName(): string;
}
