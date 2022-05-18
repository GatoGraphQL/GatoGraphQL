<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Module_Processor_CustomContentDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_AUTHOR_CONTENT = 'dataload-author-content';
    public final const COMPONENT_DATALOAD_AUTHOR_SUMMARYCONTENT = 'dataload-author-summarycontent';
    public final const COMPONENT_DATALOAD_TAG_CONTENT = 'dataload-tag-content';
    public final const COMPONENT_DATALOAD_SINGLE_CONTENT = 'dataload-single-content';
    public final const COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT = 'dataload-singleinteraction-content';
    public final const COMPONENT_DATALOAD_PAGE_CONTENT = 'dataload-page-content';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_AUTHOR_CONTENT],
            [self::class, self::COMPONENT_DATALOAD_AUTHOR_SUMMARYCONTENT],
            [self::class, self::COMPONENT_DATALOAD_TAG_CONTENT],
            [self::class, self::COMPONENT_DATALOAD_SINGLE_CONTENT],
            [self::class, self::COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT],
            [self::class, self::COMPONENT_DATALOAD_PAGE_CONTENT],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            // The Page Content block uses whichever is the current page
            self::COMPONENT_DATALOAD_PAGE_CONTENT => POP_ROUTE_DESCRIPTION,//\PoP\Root\App::getState('route'),
            self::COMPONENT_DATALOAD_AUTHOR_CONTENT => POP_ROUTE_DESCRIPTION,
            self::COMPONENT_DATALOAD_TAG_CONTENT => POP_ROUTE_DESCRIPTION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHOR_SUMMARYCONTENT:
            case self::COMPONENT_DATALOAD_AUTHOR_CONTENT:
                // Add the Sidebar on the top
                if ($sidebar = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomContentBlocks:author:sidebar',
                    [PoP_Module_Processor_CustomUserLayoutSidebars::class, PoP_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL],
                    $component
                )) {
                    $ret[] = $sidebar;
                }

                // Show the Author Description inside the widget instead of the body?
                if (PoP_ApplicationProcessors_Utils::authorFulldescription()) {
                    $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_AUTHOR];
                }
                break;

            case self::COMPONENT_DATALOAD_TAG_CONTENT:
                $ret[] = [PoP_Module_Processor_CustomTagLayoutSidebars::class, PoP_Module_Processor_CustomTagLayoutSidebars::COMPONENT_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL];
                break;

            case self::COMPONENT_DATALOAD_SINGLE_CONTENT:
                // Add the Sidebar on the top
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $top_sidebar = [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST];

                // Allow Events Manager to change the sidebar
                if ($top_sidebar = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomContentBlocks:single-sidebar:top', $top_sidebar, $post_id)) {
                    $ret[] = $top_sidebar;
                }

                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_SINGLE];

                $bottom_sidebar = [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::COMPONENT_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL];

                // Allow Events Manager to change the sidebar
                if ($bottom_sidebar = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomContentBlocks:single-sidebar:bottom', $bottom_sidebar, $post_id)) {
                    $ret[] = $bottom_sidebar;
                }
                break;

            case self::COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT:
                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_USERPOSTINTERACTION];
                break;

            case self::COMPONENT_DATALOAD_PAGE_CONTENT:
                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_SINGLE];
                break;
        }

        return $ret;
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_AUTHOR_CONTENT:
    //         case self::COMPONENT_DATALOAD_AUTHOR_SUMMARYCONTENT:
    //             return UserRequestNature::USER;

    //         case self::COMPONENT_DATALOAD_TAG_CONTENT:
    //             return TagRequestNature::TAG;

    //         case self::COMPONENT_DATALOAD_SINGLE_CONTENT:
    //         case self::COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLE_CONTENT:
            case self::COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT:
            case self::COMPONENT_DATALOAD_PAGE_CONTENT:
            case self::COMPONENT_DATALOAD_TAG_CONTENT:
            case self::COMPONENT_DATALOAD_AUTHOR_CONTENT:
            case self::COMPONENT_DATALOAD_AUTHOR_SUMMARYCONTENT:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTHOR_CONTENT:
            case self::COMPONENT_DATALOAD_AUTHOR_SUMMARYCONTENT:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::COMPONENT_DATALOAD_TAG_CONTENT:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::COMPONENT_DATALOAD_SINGLE_CONTENT:
            case self::COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::COMPONENT_DATALOAD_PAGE_CONTENT:
                return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }
}



