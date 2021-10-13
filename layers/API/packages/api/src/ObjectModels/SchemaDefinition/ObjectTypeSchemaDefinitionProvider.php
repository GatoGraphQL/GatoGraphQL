<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class ObjectTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected ObjectTypeResolverInterface $objectTypeResolver,
    ) {
        parent::__construct($objectTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_OBJECT;
    }
    
    public function getSchemaDefinition(): array
    {
        return array_merge(
            parent::getSchemaDefinition(),
            $this->getObjectTypeSchemaDefinition(false)
        );
    }
    
    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    protected function getObjectTypeSchemaDefinition(bool $useGlobal): array
    {
        // Add the directives (non-global)
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->objectTypeResolver->getSchemaDirectiveResolvers($useGlobal);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            // $schemaDefinition[SchemaDefinition::DIRECTIVES][$directiveName] = $this->objectTypeResolver->getDirectiveSchemaDefinition($directiveResolver, $options);
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
        }

        // Add the fields (non-global)
        $schemaDefinition[SchemaDefinition::FIELDS] = [];
        $schemaDefinition[SchemaDefinition::CONNECTIONS] = [];
        $schemaObjectTypeFieldResolvers = $this->objectTypeResolver->getObjectTypeFieldResolvers($useGlobal);
        foreach ($schemaObjectTypeFieldResolvers as $fieldName => $objectTypeFieldResolver) {
            $this->addFieldSchemaDefinition($schemaDefinition, $objectTypeFieldResolver, $fieldName);
        }

        $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES] = [];
        foreach ($this->objectTypeResolver->getAllImplementedInterfaceTypeResolvers() as $interfaceTypeResolver) {
            $schemaDefinition[SchemaDefinition::IMPLEMENTED_INTERFACES][] = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            $this->accessedTypeAndDirectiveResolvers[$interfaceTypeResolver::class] = $interfaceTypeResolver;
        }

        // // Add all the implemented interfaces
        // $typeInterfaceDefinitions = [];
        // foreach ($this->objectTypeResolver->getAllImplementedInterfaceTypeResolvers() as $interfaceTypeResolver) {
        //     $interfaceSchemaKey = $schemaDefinitionService->getTypeSchemaKey($interfaceTypeResolver);

        //     // Conveniently get the fields from the schema, which have already been calculated above
        //     // since they also include their interface fields
        //     $interfaceFieldNames = $interfaceTypeResolver->getFieldNamesToImplement();
        //     // The Interface fields may be implemented as either ObjectTypeFieldResolver fields or ObjectTypeFieldResolver connections,
        //     // Eg: Interface "Elemental" has field "id" and connection "self"
        //     // Merge both cases into interface fields
        //     $interfaceFields = array_filter(
        //         $schemaDefinition[SchemaDefinition::FIELDS],
        //         function ($fieldName) use ($interfaceFieldNames) {
        //             return in_array($fieldName, $interfaceFieldNames);
        //         },
        //         ARRAY_FILTER_USE_KEY
        //     );
        //     $interfaceConnections = array_filter(
        //         $schemaDefinition[SchemaDefinition::CONNECTIONS],
        //         function ($connectionName) use ($interfaceFieldNames) {
        //             return in_array($connectionName, $interfaceFieldNames);
        //         },
        //         ARRAY_FILTER_USE_KEY
        //     );
        //     $interfaceFields = array_merge(
        //         $interfaceFields,
        //         $interfaceConnections
        //     );
        //     // An interface can itself implement interfaces!
        //     $interfaceImplementedInterfaceNames = [];
        //     foreach ($interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers() as $implementedInterfaceTypeResolver) {
        //         $interfaceImplementedInterfaceNames[] = $implementedInterfaceTypeResolver->getMaybeNamespacedTypeName();
        //     }
        //     $interfaceName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
        //     // Possible types: Because we are generating this list as we go along resolving all the types, simply have this value point to a reference in $generalMessages
        //     // Just by updating that variable, it will eventually be updated everywhere
        //     $generalMessages['interfaceGeneralTypes'][$interfaceName] = $generalMessages['interfaceGeneralTypes'][$interfaceName] ?? [];
        //     $interfacePossibleTypes = &$generalMessages['interfaceGeneralTypes'][$interfaceName];
        //     // Add this type to the list of implemented types for this interface
        //     $interfacePossibleTypes[] = $typeName;
        //     $typeInterfaceDefinitions[$interfaceSchemaKey] = [
        //         SchemaDefinition::NAME => $interfaceName,
        //         SchemaDefinition::NAMESPACED_NAME => $interfaceTypeResolver->getNamespacedTypeName(),
        //         SchemaDefinition::ELEMENT_NAME => $interfaceTypeResolver->getTypeName(),
        //         SchemaDefinition::DESCRIPTION => $interfaceTypeResolver->getTypeDescription(),
        //         SchemaDefinition::FIELDS => $interfaceFields,
        //         SchemaDefinition::INTERFACES => $interfaceImplementedInterfaceNames,
        //         // The list of types that implement this interface
        //         SchemaDefinition::POSSIBLE_TYPES => &$interfacePossibleTypes,
        //     ];
        // }
        // $schemaDefinition[SchemaDefinition::INTERFACES] = $typeInterfaceDefinitions;

        // $stackMessages = [
        //     'processed' => [],
        // ];
        // $generalMessages = [
        //     'processed' => [],
        // ];
        // return $this->objectTypeResolver->objectTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, []);
        
        return $schemaDefinition;
    }

    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface> Accessed Type and Directive Resolvers
     */
    private function addFieldSchemaDefinition(
        array &$typeSchemaDefinition,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): void {
        /**
         * Fields may not be directly visible in the schema
         */
        if ($objectTypeFieldResolver->skipAddingToSchemaDefinition($this->objectTypeResolver, $fieldName)) {
            return;
        }

        // Watch out! We are passing empty $fieldArgs to generate the schema!
        $fieldSchemaDefinition = $objectTypeFieldResolver->getFieldSchemaDefinition($this->objectTypeResolver, $fieldName, []);

        // Extract the typeResolvers
        $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        $this->accessedTypeAndDirectiveResolvers[$fieldTypeResolver::class] = $fieldTypeResolver;
        unset($fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER]);

        foreach (($fieldSchemaDefinition[SchemaDefinition::ARGS] ?? []) as $fieldArgName => &$fieldArgSchemaDefinition) {
            $fieldArgTypeResolver = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$fieldArgTypeResolver::class] = $fieldArgTypeResolver;
            unset($fieldSchemaDefinition[SchemaDefinition::ARGS][$fieldArgName][SchemaDefinition::TYPE_RESOLVER]);
        }
        
        // Split the results into "fields" and "connections"
        $isConnection = $fieldTypeResolver instanceof RelationalTypeResolverInterface;
        $entry = $isConnection ?
            SchemaDefinition::CONNECTIONS :
            SchemaDefinition::FIELDS;
        $typeSchemaDefinition[$entry][$fieldName] = $fieldSchemaDefinition;
    }
}
