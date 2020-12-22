<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;

class ValidateIsUserNotLoggedInDirectiveResolver extends AbstractValidateCheckpointDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateIsUserNotLoggedIn';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function getValidationCheckpointSet(TypeResolverInterface $typeResolver): array
    {
        return UserStateCheckpointSets::NOTLOGGEDIN;
    }

    protected function getValidationFailedMessage(TypeResolverInterface $typeResolver, array $failedDataFields): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $errorMessage = $this->isValidatingDirective() ?
            $translationAPI->__('You must not be logged in to access directives in field(s) \'%s\' for type \'%s\'', 'user-state') :
            $translationAPI->__('You must not be logged in to access field(s) \'%s\' for type \'%s\'', 'user-state');
        return sprintf(
            $errorMessage,
            implode(
                $translationAPI->__('\', \''),
                $failedDataFields
            ),
            $typeResolver->getMaybeNamespacedTypeName()
        );
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('It validates if the user is not logged-in', 'component-model');
    }
}
