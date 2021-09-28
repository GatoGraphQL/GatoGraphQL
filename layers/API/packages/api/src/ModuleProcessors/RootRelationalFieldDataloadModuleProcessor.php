<?php

declare(strict_types=1);

namespace PoP\API\ModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\Schema\SchemaDefinitionServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_ROOT = 'dataload-relationalfields-root';
    protected SchemaDefinitionServiceInterface $schemaDefinitionService;

    #[Required]
    public function autowireRootRelationalFieldDataloadModuleProcessor(
        SchemaDefinitionServiceInterface $schemaDefinitionService,
    ): void {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ROOT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
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
                return $this->schemaDefinitionService->getRootTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
