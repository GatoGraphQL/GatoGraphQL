<?php

declare(strict_types=1);

namespace PoP\API\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class DuplicatePropertyDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireDuplicatePropertyDirectiveResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    
    public function getDirectiveName(): string
    {
        return 'duplicateProperty';
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

    public function getSchemaDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->translationAPI->__('Duplicate a property in the current object', 'component-model');
    }

    public function getSchemaDirectiveArgNameResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [

        ];
    }

    public function getSchemaDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            default => parent::getSchemaDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getSchemaDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            default => parent::getSchemaDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getSchemaDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?int
    {
        return match ($directiveArgName) {
            default => parent::getSchemaDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'to',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The new property name', 'component-model'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
        ];
    }

    /**
     * Duplicate a property from the current object
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
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
        $copyTo = $this->directiveArgsForSchema['to'];
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                /**
                 * The data is stored under the field's output key (not the unique one!)
                 */
                $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                if (!array_key_exists($fieldOutputKey, $dbItems[(string)$id])) {
                    $objectWarnings[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->translationAPI->__('Property \'%s\' doesn\'t exist in object with ID \'%s\', so it can\'t be copied to \'%s\''),
                            $fieldOutputKey,
                            $id,
                            $copyTo
                        ),
                    ];
                    continue;
                }
                $dbItems[(string)$id][$copyTo] = $dbItems[(string)$id][$fieldOutputKey];
            }
        }
    }
}
