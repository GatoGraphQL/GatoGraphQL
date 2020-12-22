<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;

class DisableAccessDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    const DIRECTIVE_NAME = 'disableAccess';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function validateCondition(TypeResolverInterface $typeResolver): bool
    {
        return false;
    }

    protected function getValidationFailedMessage(TypeResolverInterface $typeResolver, array $failedDataFields): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $errorMessage = $this->isValidatingDirective() ?
            $translationAPI->__('Access to directives in field(s) \'%s\' has been disabled', 'access-control') :
            $translationAPI->__('Access to field(s) \'%s\' has been disabled', 'access-control');
        return sprintf(
            $errorMessage,
            implode(
                $translationAPI->__('\', \''),
                $failedDataFields
            )
        );
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('It disables access to the field', 'access-control');
    }
}
