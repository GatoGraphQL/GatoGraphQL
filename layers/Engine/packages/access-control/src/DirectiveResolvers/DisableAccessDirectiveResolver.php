<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;

class DisableAccessDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'disableAccess';
    }

    protected function validateCondition(ObjectTypeResolverInterface $typeResolver): bool
    {
        return false;
    }

    protected function getValidationFailedMessage(ObjectTypeResolverInterface $typeResolver, array $failedDataFields): string
    {
        $errorMessage = $this->isValidatingDirective() ?
            $this->translationAPI->__('Access to directives in field(s) \'%s\' has been disabled', 'access-control') :
            $this->translationAPI->__('Access to field(s) \'%s\' has been disabled', 'access-control');
        return sprintf(
            $errorMessage,
            implode(
                $this->translationAPI->__('\', \''),
                $failedDataFields
            )
        );
    }

    public function getSchemaDirectiveDescription(ObjectTypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('It disables access to the field', 'access-control');
    }
}
