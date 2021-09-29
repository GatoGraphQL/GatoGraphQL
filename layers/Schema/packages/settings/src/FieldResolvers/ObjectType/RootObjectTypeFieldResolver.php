<?php

declare(strict_types=1);

namespace PoPSchema\Settings\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver;
    protected SettingsTypeAPIInterface $settingsTypeAPI;

    #[Required]
    public function autowireRootObjectTypeFieldResolver(
        AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver,
        SettingsTypeAPIInterface $settingsTypeAPI,
    ): void {
        $this->anyScalarScalarTypeResolver = $anyScalarScalarTypeResolver;
        $this->settingsTypeAPI = $settingsTypeAPI;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'option',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'option' => $this->translationAPI->__('Option saved in the DB', 'pop-settings'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'option' => $this->anyScalarScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'option':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'name',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The option name', 'pop-settings'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }
        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'option':
                $name = $fieldArgs['name'];
                if ($value = $this->settingsTypeAPI->getOption($name)) {
                    return $value;
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
