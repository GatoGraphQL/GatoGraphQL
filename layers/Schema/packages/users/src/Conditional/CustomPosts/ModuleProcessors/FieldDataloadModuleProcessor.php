<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Posts\ModuleProcessors\FilterInners;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST = 'dataload-relationalfields-authorcustompostlist';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST],
        );
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                return CustomPostTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                $vars = ApplicationState::getVars();
                $ret['authors'] = [
                    $vars['routing-state']['queried-object-id'],
                ];
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORCUSTOMPOSTLIST:
                return [FilterInners::class, FilterInners::MODULE_FILTERINNER_POSTS];
        }

        return parent::getFilterSubmodule($module);
    }
}



