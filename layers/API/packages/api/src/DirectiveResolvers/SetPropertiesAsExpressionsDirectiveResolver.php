<?php

declare(strict_types=1);

namespace PoP\API\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

class SetPropertiesAsExpressionsDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'setPropertiesAsExpressions';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCRIPTING;
    }

    /**
     * Do not allow dynamic fields
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return true;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('Extract a property from the current object, and set it as a expression, so it can be accessed by fieldResolvers', 'component-model');
    }

    public function getSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('Use directive `getSelfProp` together with field `extract` instead', 'component-model');
    }

    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'properties',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_IS_ARRAY => true,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The property in the current object from which to copy the data into the expressions', 'component-model'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'expressions',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_IS_ARRAY => true,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Name of the expressions. Default value: Same name as the properties', 'component-model'),
            ],
        ];
    }

    /**
     * Validate that the number of elements in the fields `properties` and `expressions` match one another
     */
    public function validateDirectiveArgumentsForSchema(TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        $directiveArgs = parent::validateDirectiveArgumentsForSchema($typeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

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
                        $this->translationAPI->__('Argument \'expressions\' has more elements than argument \'properties\', so the following expressions have been ignored: \'%s\'', 'component-model'),
                        implode($this->translationAPI->__('\', \''), array_slice($expressionsName, $propertiesCount))
                    ),
                ];
            } elseif ($expressionsNameCount < $propertiesCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->translationAPI->__('Argument \'properties\' has more elements than argument \'expressions\', so the following properties will be assigned to the destination object under their same name: \'%s\'', 'component-model'),
                        implode($this->translationAPI->__('\', \''), array_slice($properties, $expressionsNameCount))
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
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        // Send a message to the resolveAndMerge directive, indicating which properties to retrieve
        $properties = $this->directiveArgsForSchema['properties'];
        $expressionNames = $this->directiveArgsForSchema['expressions'] ?? $properties;
        $dbKey = $typeResolver->getTypeOutputName();
        foreach (array_keys($idsDataFields) as $id) {
            for ($i = 0; $i < count($properties); $i++) {
                // Validate that the property exists in the source object, either on this iteration or any previous one
                $property = $properties[$i];
                $isValueInDBItems = array_key_exists($property, $dbItems[(string)$id] ?? []);
                if (!$isValueInDBItems && !array_key_exists($property, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    $dbErrors[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->translationAPI->__('Property \'%s\' hadn\'t been set for object with ID \'%s\', so no expression has been defined', 'component-model'),
                            $property,
                            $id
                        ),
                    ];
                    continue;
                }
                // Check if the value already exists
                $expressionName = (string) $expressionNames[$i];
                $existingValue = $this->getExpressionForResultItem($id, $expressionName, $messages);
                if (!is_null($existingValue)) {
                    $dbWarnings[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->translationAPI->__('The existing value for expression \'%s\' for object with ID \'%s\' has been overriden: \'%s\'', 'component-model'),
                            $expressionName,
                            $id
                        ),
                    ];
                }
                $value = $isValueInDBItems ? $dbItems[(string)$id][$property] : $previousDBItems[$dbKey][(string)$id][$property];
                $this->addExpressionForResultItem($id, $expressionName, $value, $messages);
            }
        }
    }
}
