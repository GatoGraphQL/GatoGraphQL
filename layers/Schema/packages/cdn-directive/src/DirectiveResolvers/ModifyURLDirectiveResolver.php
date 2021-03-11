<?php

declare(strict_types=1);

namespace PoPSchema\CDNDirective\DirectiveResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\GlobalDirectiveResolverTrait;
use PoPSchema\BasicDirectives\DirectiveResolvers\AbstractTransformFieldStringValueDirectiveResolver;

/**
 * Replace the beginning section from the URL with another URL
 */
class ModifyURLDirectiveResolver extends AbstractTransformFieldStringValueDirectiveResolver
{
    use GlobalDirectiveResolverTrait;

    const DIRECTIVE_NAME = 'modifyURL';
    public function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function transformValue($value, $id, string $field, string $fieldOutputKey, TypeResolverInterface $typeResolver, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations)
    {
        /**
         * Validate it is a string
         */
        if (is_null($value) || !$this->validateTypeIsString($value, $id, $field, $fieldOutputKey, $dbErrors, $dbWarnings)) {
            return $value;
        }
        /**
         * Search from the beginning of the URL
         */
        $from = $this->getFromURLSection();
        if (substr($value, 0, strlen($from)) == $from) {
            // Do the replacement
            $to = $this->getToURLSection();
            return $to . substr($value, strlen($from));
        }
        return $value;
    }
    protected function getFromURLSection(): ?string
    {
        return $this->directiveArgsForSchema['from'];
    }
    protected function getToURLSection(): ?string
    {
        return $this->directiveArgsForSchema['to'];
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Replace the beginning path from the URL with another URL', 'basic-directives');
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'from',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The domain to be replaced, including the protocol', 'basic-directives'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'to',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The domain to use as replacement, including the protocol', 'basic-directives'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
        ];
    }
}
