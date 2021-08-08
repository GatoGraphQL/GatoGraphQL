<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Posts\ModuleProcessors\FilterInputContainerModuleProcessor;

class CategoryPostFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST = 'dataload-relationalfields-categorypostlist';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST],
        );
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return PostTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                $vars = ApplicationState::getVars();
                $ret['category-ids'] = [$vars['routing-state']['queried-object-id']];
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_POSTS];
        }

        return parent::getFilterSubmodule($module);
    }
}
