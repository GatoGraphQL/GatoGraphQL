<?php

declare(strict_types=1);

namespace PoPAPI\API\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class SetPropertiesAsExpressionsDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'setPropertiesAsExpressions';
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
        return $this->__('Extract a property from the current object, and set it as a expression, so it can be accessed by fieldResolvers', 'component-model');
    }

    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Use directive `getSelfProp` together with field `extract` instead', 'component-model');
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            'properties' => $this->getStringScalarTypeResolver(),
            'expressions' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'properties' => $this->__('The property in the current object from which to copy the data into the expressions', 'component-model'),
            'expressions' => $this->__('Name of the expressions. Default value: Same name as the properties', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'properties' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            'expressions' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    /**
     * Validate that the number of elements in the fields `properties` and `expressions` match one another
     */
    public function validateDirectiveArgumentsForSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        $directiveArgs = parent::validateDirectiveArgumentsForSchema($relationalTypeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

        if (isset($directiveArgs['expressions'])) {
            $expressionsName = $directiveArgs['expressions'];
            $properties = $directiveArgs['properties'];
            $expressionsNameCount = count($expressionsName);
            $propertiesCount = count($properties);

            // Validate that both arrays have the same number of elements
            if ($expressionsNameCount > $propertiesCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->__('Argument \'expressions\' has more elements than argument \'properties\', so the following expressions have been ignored: \'%s\'', 'component-model'),
                        implode($this->__('\', \''), array_slice($expressionsName, $propertiesCount))
                    ),
                ];
            } elseif ($expressionsNameCount < $propertiesCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->__('Argument \'properties\' has more elements than argument \'expressions\', so the following properties will be assigned to the destination object under their same name: \'%s\'', 'component-model'),
                        implode($this->__('\', \''), array_slice($properties, $expressionsNameCount))
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
        // Send a message to the resolveAndMerge directive, indicating which properties to retrieve
        $properties = $this->directiveArgsForSchema['properties'];
        $expressionNames = $this->directiveArgsForSchema['expressions'] ?? $properties;
        $dbKey = $relationalTypeResolver->getTypeOutputDBKey();
        foreach (array_keys($idsDataFields) as $id) {
            for ($i = 0; $i < count($properties); $i++) {
                // Validate that the property exists in the source object, either on this iteration or any previous one
                $property = $properties[$i];
                $isValueInDBItems = array_key_exists($property, $dbItems[(string)$id] ?? []);
                if (!$isValueInDBItems && !array_key_exists($property, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    $objectErrors[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->__('Property \'%s\' hadn\'t been set for object with ID \'%s\', so no expression has been defined', 'component-model'),
                            $property,
                            $id
                        ),
                    ];
                    continue;
                }
                // Check if the value already exists
                $expressionName = (string) $expressionNames[$i];
                $existingValue = $this->getExpressionForObject($id, $expressionName, $messages);
                if (!is_null($existingValue)) {
                    $objectWarnings[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->__('The existing value for expression \'%s\' for object with ID \'%s\' has been overriden: \'%s\'', 'component-model'),
                            $expressionName,
                            $id
                        ),
                    ];
                }
                $value = $isValueInDBItems ? $dbItems[(string)$id][$property] : $previousDBItems[$dbKey][(string)$id][$property];
                $this->addExpressionForObject($id, $expressionName, $value, $messages);
            }
        }
    }
}
