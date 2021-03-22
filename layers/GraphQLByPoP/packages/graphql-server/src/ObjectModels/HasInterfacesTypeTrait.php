<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use Exception;
use PoP\API\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\InterfaceType;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;

trait HasInterfacesTypeTrait
{
    /**
     * @var InterfaceType[]
     */
    protected array $interfaces;
    /**
     * Reference the already-registered interfaces
     */
    protected function initInterfaces(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->interfaces = [];
        $interfaceSchemaDefinitionPath = array_merge(
            $schemaDefinitionPath,
            [
                SchemaDefinition::ARGNAME_INTERFACES,
            ]
        );
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $interfaceSchemaDefinitionPointer = SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $interfaceSchemaDefinitionPath);
        foreach ($interfaceSchemaDefinitionPointer as $interfaceName) {
            // The InterfaceType must have already been registered on the root, under "interfaces"
            $schemaDefinitionID = SchemaDefinitionHelpers::getID(
                [
                    SchemaDefinition::ARGNAME_INTERFACES,
                    $interfaceName
                ]
            );
            // If the interface was not registered, that means that no FieldResolver implements it
            /**
             * @var InterfaceType
             */
            $interface = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID);
            if (is_null($interface)) {
                $translationAPI = TranslationAPIFacade::getInstance();
                throw new Exception(sprintf(
                    $translationAPI->__('No FieldResolver resolves Interface \'%s\' for schema definition path \'%s\'', 'graphql-server'),
                    $interfaceName,
                    implode(' => ', $schemaDefinitionPath)
                ));
            }
            $this->interfaces[] = $interface;
        }
    }

    public function getInterfaces(): array
    {
        return $this->interfaces;
    }
    public function getInterfaceIDs(): array
    {
        return array_map(
            function (InterfaceType $interfaceType) {
                return $interfaceType->getID();
            },
            $this->getInterfaces()
        );
    }
}
