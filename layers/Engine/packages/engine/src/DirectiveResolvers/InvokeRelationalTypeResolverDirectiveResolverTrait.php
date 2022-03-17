<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

trait InvokeRelationalTypeResolverDirectiveResolverTrait
{
    abstract protected function getDirective(): string;

    protected function maybeNestDirectiveFeedback(
        RelationalTypeResolverInterface $relationalTypeResolver,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // If there was an error, add it as nested
        $errors = $objectTypeFieldResolutionFeedbackStore->getErrors();
        if ($errors !== []) {
            $objectTypeFieldResolutionFeedbackStore->setErrors([]);
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E5,
                        [
                            $this->getDirective(),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $relationalTypeResolver,
                    [],
                    $errors
                )
            );
        }
    }
}
