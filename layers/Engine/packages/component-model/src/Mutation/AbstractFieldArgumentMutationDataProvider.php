<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractFieldArgumentMutationDataProvider extends AbstractMutationDataProvider implements FieldArgumentMutationDataProviderInterface
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
