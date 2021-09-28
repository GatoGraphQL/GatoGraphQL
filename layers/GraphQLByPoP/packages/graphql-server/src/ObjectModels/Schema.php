<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\State\ApplicationState;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\ObjectModels\ScalarType;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Facades\Schema\SchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;

class Schema
{
    /**
     * @var ScalarType[]
     */
    protected array $types;
    /**
     * @var Directive[]
     */
    protected array $directives;
    protected ?AbstractType $queryType = null;
    protected ?AbstractType $mutationType = null;
    protected ?AbstractType $subscriptionType = null;

    public function __construct(
        array $fullSchemaDefinition,
        protected string $id
    ) {
        // Initialize the global elements before anything, since they will
        // be references from the ObjectType: Fields/Connections/Directives
        // 1. Initialize all the Scalar types
        $scalarTypeNames = [
            GraphQLServerSchemaDefinition::TYPE_ID,
            GraphQLServerSchemaDefinition::TYPE_STRING,
            GraphQLServerSchemaDefinition::TYPE_INT,
            GraphQLServerSchemaDefinition::TYPE_FLOAT,
            GraphQLServerSchemaDefinition::TYPE_BOOL,
            GraphQLServerSchemaDefinition::TYPE_OBJECT,
            GraphQLServerSchemaDefinition::TYPE_ANY_SCALAR,
            GraphQLServerSchemaDefinition::TYPE_MIXED,
            GraphQLServerSchemaDefinition::TYPE_ARRAY_KEY,
            GraphQLServerSchemaDefinition::TYPE_DATE,
            GraphQLServerSchemaDefinition::TYPE_TIME,
            GraphQLServerSchemaDefinition::TYPE_URL,
            GraphQLServerSchemaDefinition::TYPE_EMAIL,
            GraphQLServerSchemaDefinition::TYPE_IP,
        ];
        $this->types = [];
        foreach ($scalarTypeNames as $typeName) {
            $typeSchemaDefinitionPath = [
                SchemaDefinition::ARGNAME_TYPES,
                $typeName,
            ];
            $this->types[] = new ScalarType(
                $fullSchemaDefinition,
                $typeSchemaDefinitionPath,
                $typeName
            );
        }

        // Enable or not to add the global fields to the schema, since they may pollute the documentation
        if (ComponentConfiguration::addGlobalFieldsToSchema()) {
            // Add the fields in the registry
            // 1. Global fields
            SchemaDefinitionHelpers::initFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::ARGNAME_GLOBAL_FIELDS,
                ]
            );
            // 2. Global connections
            SchemaDefinitionHelpers::initFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS,
                ]
            );
        }

        // Initialize the interfaces
        $interfaceSchemaDefinitionPath = [
            SchemaDefinition::ARGNAME_INTERFACES,
        ];
        $interfaceSchemaDefinitionPointer = SchemaDefinitionHelpers::advancePointerToPath(
            $fullSchemaDefinition,
            $interfaceSchemaDefinitionPath
        );
        foreach (array_keys($interfaceSchemaDefinitionPointer) as $interfaceName) {
            new InterfaceType(
                $fullSchemaDefinition,
                array_merge(
                    $interfaceSchemaDefinitionPath,
                    [
                        $interfaceName
                    ]
                )
            );
        }

        // Initialize the directives
        $this->directives = [];
        foreach ($fullSchemaDefinition[SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES] as $directiveName => $directiveDefinition) {
            $directiveSchemaDefinitionPath = [
                SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES,
                $directiveName,
            ];
            $this->directives[] = $this->getDirectiveInstance($fullSchemaDefinition, $directiveSchemaDefinitionPath);
        }

        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();

        // Initialize the different types
        // 1. queryType
        $queryTypeSchemaKey = $graphQLSchemaDefinitionService->getQueryRootTypeSchemaKey();
        $queryTypeSchemaDefinitionPath = [
            SchemaDefinition::ARGNAME_TYPES,
            $queryTypeSchemaKey,
        ];
        $this->queryType = $this->getTypeInstance($fullSchemaDefinition, $queryTypeSchemaDefinitionPath);

        // 2. mutationType
        if ($mutationTypeSchemaKey = $graphQLSchemaDefinitionService->getMutationRootTypeSchemaKey()) {
            $mutationTypeSchemaDefinitionPath = [
                SchemaDefinition::ARGNAME_TYPES,
                $mutationTypeSchemaKey,
            ];
            $this->mutationType = $this->getTypeInstance($fullSchemaDefinition, $mutationTypeSchemaDefinitionPath);
        }

        // 3. subscriptionType
        if ($subscriptionTypeSchemaKey = $graphQLSchemaDefinitionService->getSubscriptionRootTypeSchemaKey()) {
            $subscriptionTypeSchemaDefinitionPath = [
                SchemaDefinition::ARGNAME_TYPES,
                $subscriptionTypeSchemaKey,
            ];
            $this->subscriptionType = $this->getTypeInstance($fullSchemaDefinition, $subscriptionTypeSchemaDefinitionPath);
        }

        // 2. Initialize the Object and Union types from under "types" and the Interface type from under "interfaces"
        $resolvableTypes = [];
        $resolvableTypeSchemaKeys = array_diff(
            array_keys($fullSchemaDefinition[SchemaDefinition::ARGNAME_TYPES]),
            $scalarTypeNames
        );
        foreach ($resolvableTypeSchemaKeys as $typeName) {
            $typeSchemaDefinitionPath = [
                SchemaDefinition::ARGNAME_TYPES,
                $typeName,
            ];
            $resolvableTypes[] = $this->getTypeInstance($fullSchemaDefinition, $typeSchemaDefinitionPath);
        }
        $interfaceNames = array_keys($fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES]);
        // Now we can sort the interfaces, after creating new `InterfaceType`
        // Everything else was already sorted in `SchemaDefinitionReferenceRegistry`
        // Sort the elements in the schema alphabetically
        if (ComponentConfiguration::sortSchemaAlphabetically()) {
            sort($interfaceNames);
        }
        foreach ($interfaceNames as $interfaceName) {
            $interfaceSchemaDefinitionPath = [
                SchemaDefinition::ARGNAME_INTERFACES,
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
                fn (AbstractType $objectType) => $objectType->getName() != $rootTypeResolver->getTypeName()
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
    protected function getTypeInstance(array &$fullSchemaDefinition, array $typeSchemaDefinitionPath)
    {
        $typeSchemaDefinitionPointer = &$fullSchemaDefinition;
        foreach ($typeSchemaDefinitionPath as $pathLevel) {
            $typeSchemaDefinitionPointer = &$typeSchemaDefinitionPointer[$pathLevel];
        }
        $typeSchemaDefinition = $typeSchemaDefinitionPointer;
        // The type here can either be an ObjectType or a UnionType
        return ($typeSchemaDefinition[SchemaDefinition::ARGNAME_IS_UNION] ?? null) ?
            new UnionType($fullSchemaDefinition, $typeSchemaDefinitionPath) :
            new ObjectType($fullSchemaDefinition, $typeSchemaDefinitionPath);
    }
    protected function getDirectiveInstance(array &$fullSchemaDefinition, array $directiveSchemaDefinitionPath): Directive
    {
        return new Directive($fullSchemaDefinition, $directiveSchemaDefinitionPath);
    }

    public function getID(): string
    {
        return $this->id;
    }
    public function getQueryType(): AbstractType
    {
        return $this->queryType;
    }
    public function getQueryTypeID(): string
    {
        return $this->getQueryType()->getID();
    }
    public function getMutationType(): ?AbstractType
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
    public function getSubscriptionType(): ?AbstractType
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
            function (AbstractType $type) {
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
    public function getType(string $typeName): ?AbstractType
    {
        // If the provided typeName contains the namespace separator, then compare by qualifiedType
        $useQualifiedName = str_contains($typeName, SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR);
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
