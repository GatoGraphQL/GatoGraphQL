<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\TypeKinds;

class ObjectTypeSchemaDefinitionProvider extends AbstractNamedTypeSchemaDefinitionProvider
{
    /**
     * @var InterfaceTypeResolverInterface[] List of the implemented interfaces, to add this Type to the InterfaceType's POSSIBLE_TYPES
     */
    protected array $implementedInterfaceTypeResolvers = [];

    public function __construct(
        protected ObjectTypeResolverInterface $objectTypeResolver,
    ) {
        parent::__construct($objectTypeResolver);
    }

    /**
     * @return InterfaceTypeResolverInterface[] List of the implemented interfaces, to add this Type to the InterfaceType's POSSIBLE_TYPES
     */
    final public function getImplementedInterfaceTypeResolvers(): array
    {
        return $this->implementedInterfaceTypeResolvers;
    }

    public function getTypeKind(): string
    {
        return TypeKinds::OBJECT;
    }

    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $this->addDirectiveSchemaDefinitions($schemaDefinition, false);
        $this->addFieldSchemaDefinitions($schemaDefinition, false);
        $this->addInterfaceSchemaDefinitions($schemaDefinition);

        return $schemaDefinition;
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    final protected function addDirectiveSchemaDefinitions(array &$schemaDefinition, bool $useGlobal): void
    {
        // Add the directives (non-global)
        $schemaFieldDirectiveResolvers = $this->objectTypeResolver->getSchemaFieldDirectiveResolvers($useGlobal);
        if ($schemaFieldDirectiveResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        foreach ($schemaFieldDirectiveResolvers as $directiveName => $directiveResolver) {
            // Directives may not be directly visible in the schema
            if ($directiveResolver->skipExposingDirectiveInSchema($this->objectTypeResolver)) {
                continue;
            }
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
            $this->accessedTypeAndFieldDirectiveResolvers[$directiveResolver::class] = $directiveResolver;
            $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers[$directiveResolver::class] = $this->objectTypeResolver;
        }
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    final protected function addFieldSchemaDefinitions(array &$schemaDefinition, bool $useGlobal): void
    {
        $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema = $moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
        }

        // Add the fields (non-global)
        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaObjectTypeFieldResolvers = $this->objectTypeResolver->getExecutableObjectTypeFieldResolversByField($useGlobal);
        foreach ($schemaObjectTypeFieldResolvers as $fieldName => $objectTypeFieldResolver) {
            // Fields may not be directly visible in the schema
            if ($objectTypeFieldResolver->skipExposingFieldInSchema($this->objectTypeResolver, $fieldName)) {
                continue;
            }

            $fieldSchemaDefinition = $objectTypeFieldResolver->getFieldSchemaDefinition($this->objectTypeResolver, $fieldName);

            // Extract the typeResolvers
            /** @var TypeResolverInterface */
            $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndFieldDirectiveResolvers[$fieldTypeResolver::class] = $fieldTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition);

            foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
                /** @var TypeResolverInterface */
                $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];

                /**
                 * If the field arg must not be exposed, then remove it from the schema
                 */
                $skipExposingDangerousDynamicType =
                    $skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema
                    && $fieldArgTypeResolver === $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
                if ($skipExposingDangerousDynamicType || $objectTypeFieldResolver->skipExposingFieldArgInSchema($this->objectTypeResolver, $fieldName, $fieldArgName)) {
                    unset($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
                    continue;
                }

                $this->accessedTypeAndFieldDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
                SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName]);
            }
            $schemaDefinition[SchemaDefinition::FIELDS][$fieldName] = $fieldSchemaDefinition;
        }
    }

    /**
     * @param array<string,mixed> $schemaDefinition
     */
    final protected function addInterfaceSchemaDefinitions(array &$schemaDefinition): void
    {
        $this->implementedInterfaceTypeResolvers = $this->objectTypeResolver->getImplementedInterfaceTypeResolvers();
        if ($this->implementedInterfaceTypeResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        foreach ($this->implementedInterfaceTypeResolvers as $interfaceTypeResolver) {
            $interfaceTypeName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            $interfaceTypeSchemaDefinition = [
                SchemaDefinition::TYPE_RESOLVER => $interfaceTypeResolver,
            ];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
            $this->accessedTypeAndFieldDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
        }
    }
}
