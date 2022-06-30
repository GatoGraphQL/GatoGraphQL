<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;

class FieldArgumentMutationDataProvider extends AbstractFieldArgumentMutationDataProvider
{
    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        return array_map(
            fn(Argument $argument) => $argument->getName(),
            $this->field->getArguments()
        );
    }

    public function hasProperty(string $propertyName): bool
    {
        return $this->field->hasArgument($propertyName);
    }

    public function getValue(string $propertyName): mixed
    {
        return $this->field->getArgumentValue($propertyName);
    }
}
