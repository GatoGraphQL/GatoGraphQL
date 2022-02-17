<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

trait InvokeRelationalTypeResolverDirectiveResolverTrait
{
    abstract protected function getDirective(): string;
    
    /**
     * @return bool Indicates if there were errors
     */
    protected function maybeNestDirectiveFeedback(
        RelationalTypeResolverInterface $relationalTypeResolver,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        string $errorMessage,
    ): bool {
        // If there was an error, add it as nested
        $errors = $objectTypeFieldResolutionFeedbackStore->getErrors();
        if ($errors !== []) {
            $objectTypeFieldResolutionFeedbackStore->setErrors([]);
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $errorMessage,
                    'nested-directive-error',
                    LocationHelper::getNonSpecificLocation(),
                    $relationalTypeResolver,
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
