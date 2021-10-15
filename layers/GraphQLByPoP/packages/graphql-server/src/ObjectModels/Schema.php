<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionTypes as GraphQLServerSchemaDefinitionTypes;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\Schema\SchemaDefinitionTokens;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Facades\Schema\SchemaDefinitionServiceFacade;

class Schema
{
    /** @var TypeInterface[] */
    protected array $types;
    /** @var Directive[] */
    protected array $directives;
    protected ?TypeInterface $queryType = null;
    protected ?TypeInterface $mutationType = null;
    protected ?TypeInterface $subscriptionType = null;

    public function __construct(
        array $fullSchemaDefinition,
        protected string $id
    ) {
        // @todo: Check this
        // Enable or not to add the global fields to the schema, since they may pollute the documentation
        if (ComponentConfiguration::addGlobalFieldsToSchema()) {
            // Add the fields in the registry
            // 1. Global fields
            SchemaDefinitionHelpers::initFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::GLOBAL_FIELDS,
                ]
            );
            // 2. Global connections
            SchemaDefinitionHelpers::initFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::GLOBAL_CONNECTIONS,
                ]
            );
        }

        // Initialize the directives
        $this->directives = [];
        foreach ($fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES] as $directiveName => $directiveDefinition) {
            $this->directives[] = $this->getDirectiveInstance($fullSchemaDefinition, $directiveName);
        }

        // Initialize the types
        $this->types = [];
        foreach ($fullSchemaDefinition[SchemaDefinition::TYPES] as $typeKind => $typeSchemaDefinitions) {
            foreach (array_keys($typeSchemaDefinitions) as $typeName) {
                $this->types[] = $this->getTypeInstance($fullSchemaDefinition, $typeKind, $typeName);
            }
        }

        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();

        // Initialize the different types
        // 1. queryType
        $queryTypeSchemaKey = $graphQLSchemaDefinitionService->getQueryRootTypeSchemaKey();
        $queryTypeSchemaDefinitionPath = [
            SchemaDefinition::TYPES,
            $queryTypeSchemaKey,
        ];
        $this->queryType = $this->getTypeInstance($fullSchemaDefinition, $queryTypeSchemaDefinitionPath);

        // 2. mutationType
        if ($mutationTypeSchemaKey = $graphQLSchemaDefinitionService->getMutationRootTypeSchemaKey()) {
            $mutationTypeSchemaDefinitionPath = [
                SchemaDefinition::TYPES,
                $mutationTypeSchemaKey,
            ];
            $this->mutationType = $this->getTypeInstance($fullSchemaDefinition, $mutationTypeSchemaDefinitionPath);
        }

        // 3. subscriptionType
        if ($subscriptionTypeSchemaKey = $graphQLSchemaDefinitionService->getSubscriptionRootTypeSchemaKey()) {
            $subscriptionTypeSchemaDefinitionPath = [
                SchemaDefinition::TYPES,
                $subscriptionTypeSchemaKey,
            ];
            $this->subscriptionType = $this->getTypeInstance($fullSchemaDefinition, $subscriptionTypeSchemaDefinitionPath);
        }

        // 2. Initialize the Object and Union types from under "types" and the Interface type from under "interfaces"
        $resolvableTypes = [];
        $resolvableTypeSchemaKeys = array_diff(
            array_keys($fullSchemaDefinition[SchemaDefinition::TYPES]),
            $scalarTypeNames
        );
        foreach ($resolvableTypeSchemaKeys as $typeName) {
            $typeSchemaDefinitionPath = [
                SchemaDefinition::TYPES,
                $typeName,
            ];
            $resolvableTypes[] = $this->getTypeInstance($fullSchemaDefinition, $typeSchemaDefinitionPath);
        }
        $interfaceNames = array_keys($fullSchemaDefinition[SchemaDefinition::INTERFACES]);
        // Now we can sort the interfaces, after creating new `InterfaceType`
        // Everything else was already sorted in `SchemaDefinitionReferenceRegistry`
        // Sort the elements in the schema alphabetically
        if (ComponentConfiguration::sortSchemaAlphabetically()) {
            sort($interfaceNames);
        }
        foreach ($interfaceNames as $interfaceName) {
            $interfaceSchemaDefinitionPath = [
                SchemaDefinition::INTERFACES,
                $interfaceName,
            ];
            $resolvableTypes[] = new InterfaceType(
                $fullSchemaDefinition,
                $interfaceSchemaDefinitionPath
            );
        }

        // 3. Since all types have been initialized by now, we tell them to further initialize their type dependencies, since now they all exist
        // This step will initialize the dynamic Enum and InputObject types and add them to the registry
        foreach ($resolvableTypes as $resolvableType) {
            $resolvableType->initializeTypeDependencies();
        }

        /**
         * If nested mutations are disabled, we will use types QueryRoot and MutationRoot,
         * and the data for type "Root" can be safely not sent
         */
        $vars = ApplicationState::getVars();
        if (!$vars['nested-mutations-enabled']) {
            $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
            $rootTypeResolver = $schemaDefinitionService->getRootTypeResolver();
            $resolvableTypes = array_filter(
                $resolvableTypes,
                fn (TypeInterface $objectType) => $objectType->getName() != $rootTypeResolver->getTypeName()
            );
        }

        // 4. Add the Object, Union and Interface types under $resolvableTypes, and the dynamic Enum and InputObject types from the registry
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $this->types = array_merge(
            $this->types,
            $resolvableTypes,
            $schemaDefinitionReferenceRegistry->getDynamicTypes()
        );
    }
    protected function getTypeInstance(array &$fullSchemaDefinition, string $typeKind, string $typeName): TypeInterface
    {
        $typeSchemaDefinitionPath = [
            SchemaDefinition::TYPES,
            $typeKind,
            $typeName,
        ];
        // The type here can either be an ObjectType or a UnionType
        return match ($typeKind) {
            TypeKinds::OBJECT => new ObjectType($fullSchemaDefinition, $typeSchemaDefinitionPath),
            TypeKinds::INTERFACE => new InterfaceType($fullSchemaDefinition, $typeSchemaDefinitionPath),
            TypeKinds::UNION => new UnionType($fullSchemaDefinition, $typeSchemaDefinitionPath),
            TypeKinds::SCALAR => new ScalarType($fullSchemaDefinition, $typeSchemaDefinitionPath),
            TypeKinds::ENUM => new EnumType($fullSchemaDefinition, $typeSchemaDefinitionPath),
            TypeKinds::INPUT_OBJECT => new InputObjectType($fullSchemaDefinition, $typeSchemaDefinitionPath),
        };
    }
    protected function getDirectiveInstance(array &$fullSchemaDefinition, string $directiveName): Directive
    {
        $directiveSchemaDefinitionPath = [
            SchemaDefinition::GLOBAL_DIRECTIVES,
            $directiveName,
        ];
        return new Directive($fullSchemaDefinition, $directiveSchemaDefinitionPath);
    }

    public function getID(): string
    {
        return $this->id;
    }
    public function getQueryType(): TypeInterface
    {
        return $this->queryType;
    }
    public function getQueryTypeID(): string
    {
        return $this->getQueryType()->getID();
    }
    public function getMutationType(): ?TypeInterface
    {
        return $this->mutationType;
    }
    public function getMutationTypeID(): ?string
    {
        if ($mutationType = $this->getMutationType()) {
            return $mutationType->getID();
        }
        return null;
    }
    public function getSubscriptionType(): ?TypeInterface
    {
        return $this->subscriptionType;
    }
    public function getSubscriptionTypeID(): ?string
    {
        if ($subscriptionType = $this->getSubscriptionType()) {
            return $subscriptionType->getID();
        }
        return null;
    }

    public function getTypes()
    {
        return $this->types;
    }
    public function getTypeIDs(): array
    {
        return array_map(
            function (TypeInterface $type) {
                return $type->getID();
            },
            $this->getTypes()
        );
    }
    public function getDirectives()
    {
        return $this->directives;
    }
    public function getDirectiveIDs(): array
    {
        return array_map(
            function (Directive $directive) {
                return $directive->getID();
            },
            $this->getDirectives()
        );
    }
    public function getType(string $typeName): ?TypeInterface
    {
        // If the provided typeName contains the namespace separator, then compare by qualifiedType
        $useQualifiedName = str_contains($typeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
        // From all the types, get the one that has this name
        foreach ($this->types as $type) {
            // The provided `$typeName` can include namespaces or not
            $nameMatches = $useQualifiedName ?
                $typeName == $type->getNamespacedName() :
                $typeName == $type->getElementName();
            if ($nameMatches) {
                return $type;
            }
        }
        return null;
    }
    public function getTypeID(string $typeName): ?string
    {
        if ($type = $this->getType($typeName)) {
            return $type->getID();
        }
        return null;
    }
}
