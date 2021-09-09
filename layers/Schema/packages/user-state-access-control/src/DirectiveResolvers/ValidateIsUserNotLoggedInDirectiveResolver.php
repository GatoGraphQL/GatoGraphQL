<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;

class ValidateIsUserNotLoggedInDirectiveResolver extends AbstractValidateCheckpointDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateIsUserNotLoggedIn';
    }

    protected function getValidationCheckpointSet(ObjectTypeResolverInterface $objectTypeResolver): array
    {
        return UserStateCheckpointSets::NOTLOGGEDIN;
    }

    protected function getValidationFailedMessage(ObjectTypeResolverInterface $objectTypeResolver, array $failedDataFields): string
    {
        $errorMessage = $this->isValidatingDirective() ?
            $this->translationAPI->__('You must not be logged in to access directives in field(s) \'%s\' for type \'%s\'', 'user-state') :
            $this->translationAPI->__('You must not be logged in to access field(s) \'%s\' for type \'%s\'', 'user-state');
        return sprintf(
            $errorMessage,
            implode(
                $this->translationAPI->__('\', \''),
                $failedDataFields
            ),
            $objectTypeResolver->getMaybeNamespacedTypeName()
        );
    }

    public function getSchemaDirectiveDescription(ObjectTypeResolverInterface $objectTypeResolver): ?string
    {
        return $this->translationAPI->__('It validates if the user is not logged-in', 'component-model');
    }
}
