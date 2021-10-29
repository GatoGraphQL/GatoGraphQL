<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use Exception;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Misc\OperatorHelpers;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class AdvancePointerInArrayDirectiveResolver extends AbstractApplyNestedDirectivesOnArrayItemsDirectiveResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    //#[Required]
    final public function autowireAdvancePointerInArrayDirectiveResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getDirectiveName(): string
    {
        return 'advancePointerInArray';
    }

    public function getDirectiveType(): string
    {
        return DirectiveTypes::INDEXING;
    }

    /**
     * Do not allow dynamic fields
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return true;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->translationAPI->__('Apply all composed directives on the element found under the \'path\' parameter in the affected array object', 'component-model');
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                'path' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'path' => $this->translationAPI->__('Path to the element in the array', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'path' => SchemaTypeModifiers::MANDATORY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    /**
     * Directly point to the element under the specified path
     */
    protected function getArrayItems(array &$array, int | string $id, string $field, RelationalTypeResolverInterface $relationalTypeResolver, array &$objectIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$objectErrors, array &$objectWarnings, array &$objectDeprecations): ?array
    {
        $path = $this->directiveArgsForSchema['path'];

        // If the path doesn't exist, add the error and return
        try {
            $arrayItemPointer = OperatorHelpers::getPointerToArrayItemUnderPath($array, $path);
        } catch (Exception $e) {
            // Add an error and return null
            $objectErrors[(string)$id][] = [
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
     */
    protected function addProcessedItemBackToDBItems(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$dbItems,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        $id,
        string $fieldOutputKey,
        int|string $arrayItemKey,
        $arrayItemValue
    ): void {
        if (!is_array($arrayItemValue)) {
            parent::addProcessedItemBackToDBItems($relationalTypeResolver, $dbItems, $objectErrors, $objectWarnings, $objectDeprecations, $objectNotices, $objectTraces, $id, $fieldOutputKey, $arrayItemKey, $arrayItemValue);
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
                $objectErrors[(string)$id][] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => $e->getMessage(),
                ];
            }
        }
    }
}
