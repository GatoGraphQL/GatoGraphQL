<?php
namespace PoP\ExampleModules;

use PoP\ComponentModel\ModuleProcessors\AbstractDataloadModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class ModuleProcessor_Dataloads extends AbstractDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_EXAMPLE_LATESTPOSTS = 'example-latestposts';
    public const MODULE_EXAMPLE_AUTHORLATESTPOSTS = 'example-authorlatestposts';
    public const MODULE_EXAMPLE_AUTHORDESCRIPTION = 'example-authordescription';
    public const MODULE_EXAMPLE_TAGLATESTPOSTS = 'example-taglatestposts';
    public const MODULE_EXAMPLE_TAGDESCRIPTION = 'example-tagdescription';
    public const MODULE_EXAMPLE_SINGLE = 'example-single';
    public const MODULE_EXAMPLE_PAGE = 'example-page';
    public const MODULE_EXAMPLE_HOMESTATICPAGE = 'example-homestaticpage';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EXAMPLE_LATESTPOSTS],
            [self::class, self::MODULE_EXAMPLE_AUTHORLATESTPOSTS],
            [self::class, self::MODULE_EXAMPLE_AUTHORDESCRIPTION],
            [self::class, self::MODULE_EXAMPLE_TAGLATESTPOSTS],
            [self::class, self::MODULE_EXAMPLE_TAGDESCRIPTION],
            [self::class, self::MODULE_EXAMPLE_SINGLE],
            [self::class, self::MODULE_EXAMPLE_PAGE],
            [self::class, self::MODULE_EXAMPLE_HOMESTATICPAGE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EXAMPLE_AUTHORDESCRIPTION:
                $ret[] = [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_AUTHORPROPERTIES];
                break;

            case self::MODULE_EXAMPLE_TAGDESCRIPTION:
                $ret[] = [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_TAGPROPERTIES];
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_EXAMPLE_SINGLE:
            case self::MODULE_EXAMPLE_PAGE:
            case self::MODULE_EXAMPLE_TAGDESCRIPTION:
            case self::MODULE_EXAMPLE_AUTHORDESCRIPTION:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
            case self::MODULE_EXAMPLE_HOMESTATICPAGE:
                $pageTypeAPI = PageTypeAPIFacade::getInstance();
                return $pageTypeAPI->getHomeStaticPageID();
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_EXAMPLE_LATESTPOSTS:
            case self::MODULE_EXAMPLE_AUTHORLATESTPOSTS:
            case self::MODULE_EXAMPLE_TAGLATESTPOSTS:
            case self::MODULE_EXAMPLE_SINGLE:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);

            case self::MODULE_EXAMPLE_AUTHORDESCRIPTION:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::MODULE_EXAMPLE_TAGDESCRIPTION:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_EXAMPLE_PAGE:
            case self::MODULE_EXAMPLE_HOMESTATICPAGE:
                return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        $vars = ApplicationState::getVars();
        switch ($module[1]) {
            case self::MODULE_EXAMPLE_AUTHORLATESTPOSTS:
                $ret['authors'] = [$vars['routing']['queried-object-id']];
                break;

            case self::MODULE_EXAMPLE_TAGLATESTPOSTS:
                $ret['tag-ids'] = [$vars['routing']['queried-object-id']];
                break;
        }

        return $ret;
    }

    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EXAMPLE_SINGLE:
            case self::MODULE_EXAMPLE_LATESTPOSTS:
            case self::MODULE_EXAMPLE_AUTHORLATESTPOSTS:
            case self::MODULE_EXAMPLE_TAGLATESTPOSTS:
                $ret['author'] = array(
                    [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_AUTHORPROPERTIES],
                );
                $ret['comments'] = array(
                    [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_COMMENT],
                );
                break;
        }

        return $ret;
    }

    public function getDataFields(array $module, array &$props): array
    {
        $data_fields = array(
            self::MODULE_EXAMPLE_LATESTPOSTS => array('title', 'content', 'url'),
            self::MODULE_EXAMPLE_AUTHORLATESTPOSTS => array('title', 'content', 'url'),
            self::MODULE_EXAMPLE_TAGLATESTPOSTS => array('title', 'content', 'url'),
            self::MODULE_EXAMPLE_SINGLE => array('title', 'content', 'excerpt', 'status', 'date', 'commentCount', 'customPostType', 'catSlugs', 'tagNames'),
            self::MODULE_EXAMPLE_PAGE => array('title', 'content', 'date'),
            self::MODULE_EXAMPLE_HOMESTATICPAGE => array('title', 'content', 'date'),
        );
        return array_merge(
            parent::getDataFields($module, $props),
            $data_fields[$module[1]] ?? array()
        );
    }
}

