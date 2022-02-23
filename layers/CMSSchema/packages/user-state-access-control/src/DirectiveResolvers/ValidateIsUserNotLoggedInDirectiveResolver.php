<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoPCMSSchema\UserStateAccessControl\FeedbackItemProviders\FeedbackItemProvider;

class ValidateIsUserNotLoggedInDirectiveResolver extends AbstractValidateCheckpointDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateIsUserNotLoggedIn';
    }

    protected function getValidationCheckpointSet(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return UserStateCheckpointSets::NOTLOGGEDIN;
    }

    protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            $this->isValidatingDirective() ? FeedbackItemProvider::E3 : FeedbackItemProvider::E4,
            [
                implode(
                    $this->__('\', \''),
                    $failedDataFields
                ),
                $relationalTypeResolver->getMaybeNamespacedTypeName(),
            ]
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It validates if the user is not logged-in', 'component-model');
    }
}
