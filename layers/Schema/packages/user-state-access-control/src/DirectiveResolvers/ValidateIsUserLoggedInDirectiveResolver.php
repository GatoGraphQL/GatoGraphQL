<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;

class ValidateIsUserLoggedInDirectiveResolver extends AbstractValidateCheckpointDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateIsUserLoggedIn';
    }

    protected function getValidationCheckpointSet(TypeResolverInterface $typeResolver): array
    {
        return UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER;
    }

    protected function getValidationFailedMessage(TypeResolverInterface $typeResolver, array $failedDataFields): string
    {
        $errorMessage = $this->isValidatingDirective() ?
            $this->translationAPI->__('You must be logged in to access directives in field(s) \'%s\' for type \'%s\'', 'user-state') :
            $this->translationAPI->__('You must be logged in to access field(s) \'%s\' for type \'%s\'', 'user-state');
        return sprintf(
            $errorMessage,
            implode(
                $this->translationAPI->__('\', \''),
                $failedDataFields
            ),
            $typeResolver->getMaybeNamespacedTypeName()
        );
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('It validates if the user is logged-in', 'component-model');
    }
}
