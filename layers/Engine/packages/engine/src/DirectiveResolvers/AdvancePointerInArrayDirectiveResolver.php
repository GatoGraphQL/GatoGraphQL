<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use Exception;
use PoP\Engine\Misc\OperatorHelpers;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class AdvancePointerInArrayDirectiveResolver extends AbstractApplyNestedDirectivesOnArrayItemsDirectiveResolver
{
    public const DIRECTIVE_NAME = 'advancePointerInArray';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    /**
     * This is a "Scripting" type directive
     *
     * @return string
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCRIPTING;
    }

    /**
     * Do not allow dynamic fields
     *
     * @return bool
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return true;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Apply all composed directives on the element found under the \'path\' parameter in the affected array object', 'component-model');
    }

    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return array_merge(
            [
                [
                    SchemaDefinition::ARGNAME_NAME => 'path',
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Path to the element in the array', 'component-model'),
                    SchemaDefinition::ARGNAME_MANDATORY => true,
                ],
            ],
            parent::getSchemaDirectiveArgs($typeResolver)
        );
    }

    /**
     * Directly point to the element under the specified path
     *
     * @param array $array
     * @return void
     */
    protected function getArrayItems(array &$array, $id, string $field, TypeResolverInterface $typeResolver, array &$resultIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations): ?array
    {
        $path = $this->directiveArgsForSchema['path'];

        // If the path doesn't exist, add the error and return
        try {
            $arrayItemPointer = OperatorHelpers::getPointerToArrayItemUnderPath($array, $path);
        } catch (Exception $e) {
            // Add an error and return null
            $dbErrors[(string)$id][] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => $e->getMessage(),
            ];
            return null;
        }

        // Success accessing the element under that path
        return [
            $path => &$arrayItemPointer,
        ];
    }
    /**
     * Place the result for the array in the original property.
     *
     * Enables to support this query, having the translation
     * replace the original string, under the original entry in the array:
     *
     * ?query=posts.title|blockMetadata(blockName:core/paragraph)@translated<advancePointerInArray(path:meta.content)<forEach<translate(from:en,to:fr)>>>
     *
     * Otherwise it adds the results under a parallel entry, not overriding
     * the original ones.
     *
     * @param int|string $arrayItemKey
     */
    protected function addProcessedItemBackToDBItems(
        TypeResolverInterface $typeResolver,
        array &$dbItems,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        $id,
        string $fieldOutputKey,
        $arrayItemKey,
        $arrayItemValue
    ): void {
        if (!is_array($arrayItemValue)) {
            parent::addProcessedItemBackToDBItems($typeResolver, $dbItems, $dbErrors, $dbWarnings, $dbDeprecations, $dbNotices, $dbTraces, $id, $fieldOutputKey, $arrayItemKey, $arrayItemValue);
            return;
        }
        foreach ($arrayItemValue as $itemKey => $itemValue) {
            // Use function below since we may need to iterate a path
            // Eg: $arrayItemKey => "meta.content"
            try {
                OperatorHelpers::setValueToArrayItemUnderPath(
                    $dbItems[(string)$id][$fieldOutputKey][$itemKey],
                    $arrayItemKey,
                    $itemValue
                );
            } catch (Exception $e) {
                $dbErrors[(string)$id][] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => $e->getMessage(),
                ];
            }
        }
    }
}
