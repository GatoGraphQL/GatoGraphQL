<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DisableAccessDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'disableAccess';
    }

    protected function validateCondition(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return false;
    }

    protected function getValidationFailedMessage(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): string
    {
        $errorMessage = $this->isValidatingDirective() ?
            $this->__('Access to directives in field(s) \'%s\' has been disabled', 'access-control') :
            $this->__('Access to field(s) \'%s\' has been disabled', 'access-control');
        return sprintf(
            $errorMessage,
            implode(
                $this->__('\', \''),
                $failedDataFields
            )
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It disables access to the field', 'access-control');
    }
}
