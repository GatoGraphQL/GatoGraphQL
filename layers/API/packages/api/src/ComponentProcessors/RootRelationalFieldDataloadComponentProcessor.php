<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\Schema\SchemaDefinitionServiceInterface;

class RootRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT = 'dataload-relationalfields-root';

    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;

    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT],
        );
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array | null
    {
        if (App::getState('does-api-query-have-errors')) {
            return null;
        }
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT:
                return Root::ID;
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT:
                return $this->getSchemaDefinitionService()->getSchemaRootObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
