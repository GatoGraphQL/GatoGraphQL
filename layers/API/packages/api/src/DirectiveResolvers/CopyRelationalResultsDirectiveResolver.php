<?php

declare(strict_types=1);

namespace PoPAPI\API\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class CopyRelationalResultsDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }

    public function getDirectiveName(): string
    {
        return 'copyRelationalResults';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SCRIPTING;
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
        return $this->__('Copy the data from a relational object (which is one level below) to the current object', 'component-model');
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            'copyFromFields' => $this->getStringScalarTypeResolver(),
            'copyToFields' => $this->getStringScalarTypeResolver(),
            'keepRelationalIDs' => $this->getBooleanScalarTypeResolver(),
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'copyFromFields' => $this->__('The fields in the relational object from which to copy the data', 'component-model'),
            'copyToFields' => $this->__('The fields in the current object to which copy the data. Default value: Same fields provided through \'copyFromFields\' argument', 'component-model'),
            'keepRelationalIDs' => $this->__('Indicate if the properties are placed under the relational ID as keys (`true`) or as a one-dimensional array (`false`)', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            'keepRelationalIDs' => false,
            default => parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'copyFromFields' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            'copyToFields' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    /**
     * Validate that the number of elements in the fields `copyToFields` and `copyFromFields` match one another
     */
    public function validateDirectiveArgumentsForSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        $directiveArgs = parent::validateDirectiveArgumentsForSchema($relationalTypeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

        if (isset($directiveArgs['copyToFields'])) {
            $copyToFields = $directiveArgs['copyToFields'];
            $copyFromFields = $directiveArgs['copyFromFields'];
            $copyToFieldsCount = count($copyToFields);
            $copyFromFieldsCount = count($copyFromFields);

            // Validate that both arrays have the same number of elements
            if ($copyToFieldsCount > $copyFromFieldsCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->__('Argument \'copyToFields\' has more elements than argument \'copyFromFields\', so the following fields have been ignored: \'%s\'', 'component-model'),
                        implode($this->__('\', \''), array_slice($copyToFields, $copyFromFieldsCount))
                    ),
                ];
            } elseif ($copyToFieldsCount < $copyFromFieldsCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->__('Argument \'copyFromFields\' has more elements than argument \'copyToFields\', so the following fields will be copied to the destination object under their same field name: \'%s\'', 'component-model'),
                        implode($this->__('\', \''), array_slice($copyFromFields, $copyToFieldsCount))
                    ),
                ];
            }
        }

        return $directiveArgs;
    }

    /**
     * Copy the data under the relational object into the current object
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idsDataFields,
        array $succeedingPipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$succeedingPipelineIDsDataFields,
        array &$dbItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        // From the typeResolver, obtain under what type the data for the current object is stored
        $dbKey = $targetObjectTypeResolver = $unionTypeResolver = null;
        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
        } else {
            /** @var ObjectTypeResolverInterface */
            $targetObjectTypeResolver = $relationalTypeResolver;
            $dbKey = $targetObjectTypeResolver->getTypeOutputDBKey();
        }

        $copyFromFields = $this->directiveArgsForSchema['copyFromFields'];
        $copyToFields = $this->directiveArgsForSchema['copyToFields'] ?? $copyFromFields;
        $keepRelationalIDs = $this->directiveArgsForSchema['keepRelationalIDs'];

        // Copy the data from each of the relational object fields to the current object
        foreach ($idsDataFields as $id => $dataFields) {
            $object = $objectIDItems[$id];
            if ($isUnionTypeResolver) {
                $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
                $dbKey = $targetObjectTypeResolver->getTypeOutputDBKey();
            }
            foreach ($dataFields['direct'] as $relationalField) {
                /**
                 * The data is stored under the field's output key.
                 *
                 * Watch out! Must fetch content under the already-used field's output key,
                 * so must use `getFieldOutputKey` instead of `getUniqueFieldOutputKey`,
                 * otherwise it will not find the value since it will produce a different entry.
                 *
                 * For instance:
                 *
                 *     /?postId=1
                 *       &query=
                 *         post($postId)@post.content|date(d/m/Y)@date;
                 *         post($postId)@post<copyRelationalResults([content,date],[postContent,postDate])>
                 *
                 * In this query, the unique field output key for "post($postId)@post"
                 * is "post" and for "post($postId)@post<copyRelationalResults([content,date],[postContent,postDate])>"
                 * it is "post-1".
                 */
                $relationalFieldOutputKey = $this->getFieldQueryInterpreter()->getFieldOutputKey($relationalField);

                // Make sure the field is relational, and not a scalar or enum
                $fieldTypeResolver = $targetObjectTypeResolver->getFieldTypeResolver($relationalField);
                if (!($fieldTypeResolver instanceof RelationalTypeResolverInterface)) {
                    $objectErrors[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->__('Field \'%s\' is not a connection, so it cannot have data properties', 'component-model'),
                            $relationalFieldOutputKey
                        ),
                    ];
                    continue;
                }
                $relationalFieldTypeResolver = $fieldTypeResolver;

                // Validate that the current object has `relationalField` property set
                // Since we are fetching from a relational object (placed one level below in the iteration stack), the value could've been set only in a previous iteration
                // Then it must be in $previousDBItems (it can't be in $dbItems unless set by chance, because the same IDs were involved for a possibly different query)
                if (!array_key_exists($relationalFieldOutputKey, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    if ($relationalFieldOutputKey != $relationalField) {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so no data can be copied', 'component-model'),
                                $relationalFieldOutputKey,
                                $id
                            ),
                        ];
                    } else {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so no data can be copied', 'component-model'),
                                $relationalField,
                                $id
                            ),
                        ];
                    }
                    continue;
                }

                $relationalFieldTypeOutputDBKey = $relationalFieldTypeResolver->getTypeOutputDBKey();
                $isUnionRelationalFieldTypeResolver = $relationalFieldTypeResolver instanceof UnionTypeResolverInterface;
                for ($i = 0; $i < count($copyFromFields); $i++) {
                    $copyFromField = $copyFromFields[$i];
                    $copyToField = $copyToFields[$i] ?? $copyFromFields[$i];

                    // If the destination field already exists, warn that it will be overriden
                    $isTargetValueInDBItems = array_key_exists($copyToField, $dbItems[(string)$id] ?? []);
                    if ($isTargetValueInDBItems || array_key_exists($copyToField, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                        $objectWarnings[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->__('The existing value for field \'%s\' from object with ID \'%s\' has been overriden: \'%s\'', 'component-model'),
                                $copyToField,
                                $id,
                                $isTargetValueInDBItems ?
                                    $dbItems[(string)$id][$copyToField] :
                                    $previousDBItems[$dbKey][(string)$id][$copyToField]
                            ),
                        ];
                    }
                    // Copy the properties into the array
                    $dbItems[(string)$id][$copyToField] = [];

                    // Obtain the DBKey under which the relationalField is stored in the database
                    if ($isUnionRelationalFieldTypeResolver) {
                        // If the relational type data resolver is union, we must use the corresponding IDs from $unionDBKeyIDs, which contain the type in addition to the ID
                        $relationalFieldIDs = $unionDBKeyIDs[$dbKey][(string)$id][$relationalFieldOutputKey];
                    } else {
                        // Otherwise, directly use the IDs from the object
                        $relationalFieldIDs = $previousDBItems[$dbKey][(string)$id][$relationalFieldOutputKey];
                    }

                    // $relationalFieldIDs can be an array of IDs, or a single item. In the latter case, copy the property directly. In the former one, copy it under an array,
                    // either with the ID of relational object as key, or as a normal one-dimension array using no particular keys
                    $copyStraight = false;
                    if (!is_array($relationalFieldIDs)) {
                        $relationalFieldIDs = [$relationalFieldIDs];
                        $copyStraight = true;
                    }

                    foreach ($relationalFieldIDs as $relationalFieldID) {
                        // Validate that the source field has been set.
                        if (!array_key_exists($copyFromField, $previousDBItems[$relationalFieldTypeOutputDBKey][(string)$relationalFieldID] ?? [])) {
                            $objectErrors[(string)$id][] = [
                                Tokens::PATH => [$this->directive],
                                Tokens::MESSAGE => sprintf(
                                    $this->__('Field \'%s\' hadn\'t been set for object of entity \'%s\' and ID \'%s\', so no data can be copied', 'component-model'),
                                    $copyFromField,
                                    $relationalFieldTypeOutputDBKey,
                                    $relationalFieldID
                                ),
                            ];
                            continue;
                        }
                        if ($copyStraight) {
                            $dbItems[(string)$id][$copyToField] = $previousDBItems[$relationalFieldTypeOutputDBKey][(string)$relationalFieldID][$copyFromField];
                        } elseif ($keepRelationalIDs) {
                            $dbItems[(string)$id][$copyToField][(string)$relationalFieldID] = $previousDBItems[$relationalFieldTypeOutputDBKey][(string)$relationalFieldID][$copyFromField];
                        } else {
                            $dbItems[(string)$id][$copyToField][] = $previousDBItems[$relationalFieldTypeOutputDBKey][(string)$relationalFieldID][$copyFromField];
                        }
                    }
                }
            }
        }
    }
}
