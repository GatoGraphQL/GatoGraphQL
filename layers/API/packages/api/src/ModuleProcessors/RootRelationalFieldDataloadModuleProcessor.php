<?php

declare(strict_types=1);

namespace PoPAPI\API\ModuleProcessors;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\Schema\SchemaDefinitionServiceInterface;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public final const MODULE_DATALOAD_RELATIONALFIELDS_ROOT = 'dataload-relationalfields-root';

    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;

    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array | null
    {
        if (App::getState('does-api-query-have-errors')) {
            return null;
        }
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT:
                return Root::ID;
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT:
                return $this->getSchemaDefinitionService()->getSchemaRootObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
