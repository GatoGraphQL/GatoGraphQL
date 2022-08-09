<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

class ObjectFieldValuePromise implements ResolvableOnObjectValueResolutionPromiseInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        public readonly FieldInterface $field,
    ) {
    }

    public function resolveValue(): mixed
    {
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $objectResolvedFieldValues = App::getState('engine-iteration-resolved-object-field-values');
        if (!$objectResolvedFieldValues->contains($this->field)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('The ObjectFieldValuePromise cannot resolve field \'%s\'', 'graphql-parser'),
                    $this->field->asFieldOutputQueryString()
                )
            );
        }

        return $objectResolvedFieldValues[$this->field];
    }
}
