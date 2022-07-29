<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

class FieldValuePromise
{
    use StandaloneServiceTrait;

    public function __construct(
        public readonly FieldInterface $field,
    ) {
    }

    public function resolveValue(): mixed
    {
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $resolvedFieldValues = App::getState('engine-iteration-object-resolved-field-values');
        if (!$resolvedFieldValues->contains($this->field)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('The FieldValuePromise cannot resolve field \'%s\'', 'graphql-parser'),
                    $this->field->asFieldOutputQueryString()
                )
            );
        }

        return $resolvedFieldValues[$this->field];
    }
}
