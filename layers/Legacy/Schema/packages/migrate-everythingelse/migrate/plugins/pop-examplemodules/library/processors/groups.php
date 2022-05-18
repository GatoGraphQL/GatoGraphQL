<?php
namespace PoP\ExampleModules;
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor;
use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

class ComponentProcessor_Groups extends AbstractComponentProcessor
{
    public final const MODULE_EXAMPLE_HOME = 'example-home';
    public final const MODULE_EXAMPLE_AUTHOR = 'example-author';
    public final const MODULE_EXAMPLE_TAG = 'example-tag';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EXAMPLE_HOME],
            [self::class, self::MODULE_EXAMPLE_AUTHOR],
            [self::class, self::MODULE_EXAMPLE_TAG],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        switch ($module[1]) {
            case self::MODULE_EXAMPLE_HOME:
                $pageTypeAPI = PageTypeAPIFacade::getInstance();
                if ($pageTypeAPI->getHomeStaticPageID()) {
                    $ret[] = [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_HOMESTATICPAGE];
                } else {
                    $ret[] = [ComponentProcessor_Layouts::class, ComponentProcessor_Layouts::MODULE_EXAMPLE_HOMEWELCOME];
                    $ret[] = [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_LATESTPOSTS];
                }
                break;

            case self::MODULE_EXAMPLE_AUTHOR:
                $ret[] = [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_AUTHORDESCRIPTION];
                $ret[] = [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_AUTHORLATESTPOSTS];
                break;

            case self::MODULE_EXAMPLE_TAG:
                $ret[] = [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_TAGDESCRIPTION];
                $ret[] = [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_TAGLATESTPOSTS];
                break;
        }

        return $ret;
    }
}

