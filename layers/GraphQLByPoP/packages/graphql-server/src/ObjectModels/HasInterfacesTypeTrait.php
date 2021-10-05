<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use Exception;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;

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
                SchemaDefinition::INTERFACES,
            ]
        );
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $interfaceSchemaDefinitionPointer = SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $interfaceSchemaDefinitionPath);
        foreach ($interfaceSchemaDefinitionPointer as $interfaceName) {
            // The InterfaceType must have already been registered on the root, under "interfaces"
            $schemaDefinitionID = SchemaDefinitionHelpers::getID(
                [
                    SchemaDefinition::INTERFACES,
                    $interfaceName
                ]
            );
            // If the interface was not registered, that means that no ObjectTypeFieldResolver implements it
            $interface = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID);
            if ($interface === null) {
                $translationAPI = TranslationAPIFacade::getInstance();
                throw new Exception(sprintf(
                    $translationAPI->__('No ObjectTypeFieldResolver resolves Interface \'%s\' for schema definition path \'%s\'', 'graphql-server'),
                    $interfaceName,
                    implode(' => ', $schemaDefinitionPath)
                ));
            }
            /** @var InterfaceType */
            $interface = $interface;
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
