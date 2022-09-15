<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\ObjectFieldValuePromiseException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

class ObjectFieldValuePromise implements ValueResolutionPromiseInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        public readonly FieldInterface $field,
    ) {
    }

    public function resolveValue(): mixed
    {
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $objectResolvedFieldValues = App::getState('engine-iteration-object-resolved-field-values');
        if (!$objectResolvedFieldValues->contains($this->field)) {
            throw new ObjectFieldValuePromiseException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E11,
                    [
                        $this->field->asFieldOutputQueryString(),
                    ]
                ),
                $this->field
            );
        }

        return $objectResolvedFieldValues[$this->field];
    }

    /**
     * The field/directiveArgs containing the promise must be resolved:
     *
     * Object by object
     */
    public function mustResolveOnObject(): bool
    {
        return true;
    }
}
