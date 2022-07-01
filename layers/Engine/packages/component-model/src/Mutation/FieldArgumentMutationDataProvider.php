<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldArgumentMutationDataProvider extends MutationDataProvider implements FieldArgumentMutationDataProviderInterface
{
    public function __construct(
        protected FieldInterface $field,
        array $propertyValues = [],
    ) {
        parent::__construct($propertyValues);
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }
    
    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        return array_unique(array_merge(
            parent::getPropertyNames(),
            array_map(
                fn(Argument $argument) => $argument->getName(),
                $this->field->getArguments()
            )
        ));
    }

    public function hasProperty(string $propertyName): bool
    {
        if ($this->field->hasArgument($propertyName)) {
            return true;
        }
        return parent::hasProperty($propertyName);
    }

    public function getValue(string $propertyName): mixed
    {
        if ($this->field->hasArgument($propertyName)) {
            return $this->field->getArgumentValue($propertyName);
        }
        return parent::getValue($propertyName);
    }
}
