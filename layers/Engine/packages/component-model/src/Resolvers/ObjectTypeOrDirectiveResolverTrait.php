<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use stdClass;

trait ObjectTypeOrDirectiveResolverTrait
{
    /**
     * Get the field/directive arguments which have a default value.
     *
     * Set the missing InputObject as {} to give it a chance to set
     * its default input values
     *
     * @return array<string,mixed>
     */
    final protected function getFieldOrDirectiveArgumentNameDefaultValues(array $fieldOrDirectiveArgsSchemaDefinition): array
    {
        $fieldOrDirectiveArgNameDefaultValues = [];
        foreach ($fieldOrDirectiveArgsSchemaDefinition as $fieldOrDirectiveSchemaDefinitionArg) {
            if (\array_key_exists(SchemaDefinition::DEFAULT_VALUE, $fieldOrDirectiveSchemaDefinitionArg)) {
                // If it has a default value, set it
                $fieldOrDirectiveArgNameDefaultValues[$fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::NAME]] = $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::DEFAULT_VALUE];
                continue;
            }
            if (
                // If it is a non-mandatory InputObject, set {}
                // (If it is mandatory, don't set a value as to let the validation fail)
                $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER] instanceof InputObjectTypeResolverInterface
                && !($fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::MANDATORY] ?? false)
            ) {
                $fieldOrDirectiveArgNameDefaultValues[$fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::NAME]] = new stdClass();
            }
        }
        return $fieldOrDirectiveArgNameDefaultValues;
    }

    /**
     * @param array<string,mixed> $fieldOrDirectiveData
     * @param array<string,mixed> $argumentNameDefaultValues
     * @return array<string,mixed>
     */
    final protected function addDefaultFieldOrDirectiveArguments(
        array $fieldOrDirectiveData,
        array $argumentNameDefaultValues,
    ): array {
        $completedFieldOrDirectiveData = [];
        foreach ($argumentNameDefaultValues as $argName => $argDefaultValue) {
            if (array_key_exists($argName, $fieldOrDirectiveData)) {
                $completedFieldOrDirectiveData[$argName] = $fieldOrDirectiveData[$argName];
                continue;
            }            
            $fieldOrDirectiveData[$argName] = $argDefaultValue;
        }
        return $completedFieldOrDirectiveData;
    }

    /**
     * @todo Fix integrate with Directive
     * @todo Replace with addDefaultFieldOrDirectiveArguments
     * @param array<string,mixed> $argumentNameDefaultValues
     */
    final protected function deprecatedIntegrateDefaultFieldOrDirectiveArguments(
        FieldInterface|Directive $fieldOrDirective,
        array $argumentNameDefaultValues,
    ): void {
        foreach ($argumentNameDefaultValues as $argName => $argValue) {
            // If the argument does not exist, add a new one
            if ($fieldOrDirective->hasArgument($argName)) {
                continue;
            }
            
            $directiveArgValueAST = $this->getArgumentValueAsAST($argValue);
            $fieldOrDirective->addArgument(
                new Argument(
                    $argName,
                    $directiveArgValueAST,
                    LocationHelper::getNonSpecificLocation()
                )
            );
        }
    }

    final protected function getArgumentValueAsAST(mixed $argumentValue): WithValueInterface
    {
        if (is_array($argumentValue)) {
            return new InputList(
                $argumentValue,
                LocationHelper::getNonSpecificLocation()
            );
        }
        if ($argumentValue instanceof stdClass) {
            return new InputObject(
                $argumentValue,
                LocationHelper::getNonSpecificLocation()
            );
        }
        return new Literal(
            $argumentValue,
            LocationHelper::getNonSpecificLocation()
        );
    }
}
