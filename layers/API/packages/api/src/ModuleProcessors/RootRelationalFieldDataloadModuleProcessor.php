<?php

declare(strict_types=1);

namespace PoP\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\Engine\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\Engine\ObjectModels\Root;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_ROOT = 'dataload-relationalfields-root';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT:
                return Root::ID;
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT:
                $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
                return $schemaDefinitionService->getRootTypeResolverClass();
        }

        return parent::getRelationalTypeResolverClass($module);
    }
}
