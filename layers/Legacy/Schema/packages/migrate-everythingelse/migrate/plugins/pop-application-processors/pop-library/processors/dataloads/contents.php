<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Module_Processor_CustomContentDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectModuleProcessorTrait;

    public final const MODULE_DATALOAD_AUTHOR_CONTENT = 'dataload-author-content';
    public final const MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT = 'dataload-author-summarycontent';
    public final const MODULE_DATALOAD_TAG_CONTENT = 'dataload-tag-content';
    public final const MODULE_DATALOAD_SINGLE_CONTENT = 'dataload-single-content';
    public final const MODULE_DATALOAD_SINGLEINTERACTION_CONTENT = 'dataload-singleinteraction-content';
    public final const MODULE_DATALOAD_PAGE_CONTENT = 'dataload-page-content';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTHOR_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT],
            [self::class, self::MODULE_DATALOAD_TAG_CONTENT],
            [self::class, self::MODULE_DATALOAD_SINGLE_CONTENT],
            [self::class, self::MODULE_DATALOAD_SINGLEINTERACTION_CONTENT],
            [self::class, self::MODULE_DATALOAD_PAGE_CONTENT],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            // The Page Content block uses whichever is the current page
            self::MODULE_DATALOAD_PAGE_CONTENT => POP_ROUTE_DESCRIPTION,//\PoP\Root\App::getState('route'),
            self::MODULE_DATALOAD_AUTHOR_CONTENT => POP_ROUTE_DESCRIPTION,
            self::MODULE_DATALOAD_TAG_CONTENT => POP_ROUTE_DESCRIPTION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT:
            case self::MODULE_DATALOAD_AUTHOR_CONTENT:
                // Add the Sidebar on the top
                if ($sidebar = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomContentBlocks:author:sidebar',
                    [PoP_Module_Processor_CustomUserLayoutSidebars::class, PoP_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL],
                    $module
                )) {
                    $ret[] = $sidebar;
                }

                // Show the Author Description inside the widget instead of the body?
                if (PoP_ApplicationProcessors_Utils::authorFulldescription()) {
                    $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_AUTHOR];
                }
                break;

            case self::MODULE_DATALOAD_TAG_CONTENT:
                $ret[] = [PoP_Module_Processor_CustomTagLayoutSidebars::class, PoP_Module_Processor_CustomTagLayoutSidebars::MODULE_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL];
                break;

            case self::MODULE_DATALOAD_SINGLE_CONTENT:
                // Add the Sidebar on the top
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $top_sidebar = [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST];

                // Allow Events Manager to change the sidebar
                if ($top_sidebar = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomContentBlocks:single-sidebar:top', $top_sidebar, $post_id)) {
                    $ret[] = $top_sidebar;
                }

                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE];

                $bottom_sidebar = [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::MODULE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL];

                // Allow Events Manager to change the sidebar
                if ($bottom_sidebar = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomContentBlocks:single-sidebar:bottom', $bottom_sidebar, $post_id)) {
                    $ret[] = $bottom_sidebar;
                }
                break;

            case self::MODULE_DATALOAD_SINGLEINTERACTION_CONTENT:
                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_USERPOSTINTERACTION];
                break;

            case self::MODULE_DATALOAD_PAGE_CONTENT:
                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE];
                break;
        }

        return $ret;
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHOR_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAG_CONTENT:
    //             return TagRequestNature::TAG;

    //         case self::MODULE_DATALOAD_SINGLE_CONTENT:
    //         case self::MODULE_DATALOAD_SINGLEINTERACTION_CONTENT:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_CONTENT:
            case self::MODULE_DATALOAD_SINGLEINTERACTION_CONTENT:
            case self::MODULE_DATALOAD_PAGE_CONTENT:
            case self::MODULE_DATALOAD_TAG_CONTENT:
            case self::MODULE_DATALOAD_AUTHOR_CONTENT:
            case self::MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHOR_CONTENT:
            case self::MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::MODULE_DATALOAD_TAG_CONTENT:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_DATALOAD_SINGLE_CONTENT:
            case self::MODULE_DATALOAD_SINGLEINTERACTION_CONTENT:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::MODULE_DATALOAD_PAGE_CONTENT:
                return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }
}



