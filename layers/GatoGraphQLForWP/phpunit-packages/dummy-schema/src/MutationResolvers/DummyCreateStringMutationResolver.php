<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

class DummyCreateStringMutationResolver extends AbstractMutationResolver
{
    protected int $counter = 0;

    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $this->counter++;
        return sprintf(
            $this->__('Hello world #%s', 'dummy-schema'),
            $this->counter
        );
    }
}
