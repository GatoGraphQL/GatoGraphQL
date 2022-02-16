<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

trait InvokeRelationalTypeResolverDirectiveResolverTrait
{
    abstract protected function getDirective(): string;
    
    /**
     * @return bool Indicates if there were errors
     */
    protected function transferNestedDirectiveFeedback(
        int | string $id,
        string $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        string $errorMessage,
    ): bool {
        // Transfer the feedback, but without the errors
        $errors = $objectTypeFieldResolutionFeedbackStore->getErrors();
        $objectTypeFieldResolutionFeedbackStore->setErrors([]);
        $engineIterationFeedbackStore->incorporate(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $field,
            $id,
        );

        // If there was an error, add it as nested
        if ($errors !== []) {
            $engineIterationFeedbackStore->objectFeedbackStore->addError(
                new ObjectFeedback(
                    $errorMessage,
                    'nested-directive-error',
                    LocationHelper::getNonSpecificLocation(),
                    $relationalTypeResolver,
                    $field,
                    $id,
                    $this->getDirective(),
                    [],
                    [],
                    $errors
                )
            );
            return true;
        }
        return false;
    }
}
