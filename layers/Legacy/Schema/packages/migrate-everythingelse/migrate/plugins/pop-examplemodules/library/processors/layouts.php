<?php
namespace PoP\ExampleModules;

use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;

class ModuleProcessor_Layouts extends AbstractModuleProcessor
{
    public final const MODULE_EXAMPLE_404 = 'example-404';
    public final const MODULE_EXAMPLE_HOMEWELCOME = 'example-homewelcome';
    public final const MODULE_EXAMPLE_COMMENT = 'example-comment';
    public final const MODULE_EXAMPLE_AUTHORPROPERTIES = 'example-authorproperties';
    public final const MODULE_EXAMPLE_TAGPROPERTIES = 'example-tagproperties';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EXAMPLE_404],
            [self::class, self::MODULE_EXAMPLE_HOMEWELCOME],
            [self::class, self::MODULE_EXAMPLE_COMMENT],
            [self::class, self::MODULE_EXAMPLE_AUTHORPROPERTIES],
            [self::class, self::MODULE_EXAMPLE_TAGPROPERTIES],
        );
    }

    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        switch ($module[1]) {
            case self::MODULE_EXAMPLE_COMMENT:
                $ret[] = 'content';
                break;

            case self::MODULE_EXAMPLE_AUTHORPROPERTIES:
                $ret = array_merge(
                    $ret,
                    array('displayName', 'description', 'url')
                );
                break;

            case self::MODULE_EXAMPLE_TAGPROPERTIES:
                $ret = array_merge(
                    $ret,
                    array('name', 'slug', 'description', 'count')
                );
                break;
        }

        return $ret;
    }

    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EXAMPLE_COMMENT:
                $ret['author'] = array(
                    [self::class, self::MODULE_EXAMPLE_AUTHORPROPERTIES],
                );
                break;
        }

        return $ret;
    }
}

