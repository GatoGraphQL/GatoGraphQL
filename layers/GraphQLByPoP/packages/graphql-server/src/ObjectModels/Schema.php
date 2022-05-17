<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\TypeKinds;
use PoP\Root\Services\StandaloneServiceTrait;
use PoP\ComponentModel\Schema\SchemaDefinitionTokens;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Exception\ImpossibleToHappenException;

class Schema
{
    use StandaloneServiceTrait;

    /** @var NamedTypeInterface[] */
    protected array $types;
    /** @var Directive[] */
    protected array $directives;
    protected SchemaExtensions $schemaExtensions;

    public function __construct(
        array &$fullSchemaDefinition,
        protected string $id
    ) {
        // Enable or not to add the global fields to the schema, since they may pollute the documentation
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->exposeGlobalFieldsInGraphQLSchema()) {
            // Add the global fields in the registry
            SchemaDefinitionHelpers::createFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::GLOBAL_FIELDS,
                ]
            );
        }

        // Initialize the directives
        $this->directives = [];
        foreach (array_keys($fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            $this->directives[] = $this->getDirectiveInstance($fullSchemaDefinition, $directiveName);
        }

        // Initialize the types
        $this->types = [];
        foreach ($fullSchemaDefinition[SchemaDefinition::TYPES] as $typeKind => $typeSchemaDefinitions) {
            foreach (array_keys($typeSchemaDefinitions) as $typeName) {
                $this->types[] = $this->getTypeInstance($fullSchemaDefinition, $typeKind, $typeName);
            }
        }

        $schemaExtensionsSchemaDefinitionPath = [
            SchemaDefinition::EXTENSIONS,
        ];
        $this->schemaExtensions = new SchemaExtensions($fullSchemaDefinition, $schemaExtensionsSchemaDefinitionPath);
    }
    protected function getTypeInstance(array &$fullSchemaDefinition, string $typeKind, string $typeName): NamedTypeInterface
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
            default => throw new ImpossibleToHappenException(sprintf(
                $this->__('Unknown type kind \'%s\'', 'graphql-server'),
                $typeKind
            )),
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
    public function getQueryRootObjectTypeID(): string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        return $this->getObjectTypeID($graphQLSchemaDefinitionService->getSchemaQueryRootObjectTypeResolver());
    }
    public function getMutationRootObjectTypeID(): ?string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        if ($mutationRootTypeResolver = $graphQLSchemaDefinitionService->getSchemaMutationRootObjectTypeResolver()) {
            return $this->getObjectTypeID($mutationRootTypeResolver);
        }
        return null;
    }
    public function getSubscriptionRootObjectTypeID(): ?string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        if ($subscriptionRootTypeResolver = $graphQLSchemaDefinitionService->getSchemaSubscriptionRootTypeResolver()) {
            return $this->getObjectTypeID($subscriptionRootTypeResolver);
        }
        return null;
    }
    final protected function getObjectTypeID(ObjectTypeResolverInterface $objectTypeResolver): string
    {
        return SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID([
            SchemaDefinition::TYPES,
            TypeKinds::OBJECT,
            $objectTypeResolver->getMaybeNamespacedTypeName(),
        ]);
    }

    /**
     * @return string[]
     */
    public function getTypeIDs(): array
    {
        return array_map(
            function (NamedTypeInterface $type) {
                return $type->getID();
            },
            $this->types
        );
    }
    /**
     * @return string[]
     */
    public function getDirectiveIDs(): array
    {
        return array_map(
            function (Directive $directive) {
                return $directive->getID();
            },
            $this->directives
        );
    }
    public function getType(string $typeName): ?NamedTypeInterface
    {
        // If the provided typeName contains the namespace separator, then compare by qualifiedType
        $useQualifiedName = str_contains($typeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
        // From all the types, get the one that has this name
        foreach ($this->types as $type) {
            // The provided `$typeName` can include namespaces or not
            $nameMatches = $useQualifiedName ?
                $typeName === $type->getNamespacedName() :
                $typeName === $type->getElementName();
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

    public function getExtensions(): SchemaExtensions
    {
        return $this->schemaExtensions;
    }
}
