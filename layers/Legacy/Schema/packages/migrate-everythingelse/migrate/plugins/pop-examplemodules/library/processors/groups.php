<?php
namespace PoP\ExampleModules;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

class ModuleProcessor_Groups extends AbstractModuleProcessor
{
    public const MODULE_EXAMPLE_HOME = 'example-home';
    public const MODULE_EXAMPLE_AUTHOR = 'example-author';
    public const MODULE_EXAMPLE_TAG = 'example-tag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EXAMPLE_HOME],
            [self::class, self::MODULE_EXAMPLE_AUTHOR],
            [self::class, self::MODULE_EXAMPLE_TAG],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EXAMPLE_HOME:
                $pageTypeAPI = PageTypeAPIFacade::getInstance();
                if ($pageTypeAPI->getHomeStaticPageID()) {
                    $ret[] = [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_HOMESTATICPAGE];
                } else {
                    $ret[] = [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_HOMEWELCOME];
                    $ret[] = [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_LATESTPOSTS];
                }
                break;

            case self::MODULE_EXAMPLE_AUTHOR:
                $ret[] = [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_AUTHORDESCRIPTION];
                $ret[] = [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_AUTHORLATESTPOSTS];
                break;

            case self::MODULE_EXAMPLE_TAG:
                $ret[] = [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_TAGDESCRIPTION];
                $ret[] = [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_TAGLATESTPOSTS];
                break;
        }

        return $ret;
    }
}

