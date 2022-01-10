<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_CustomContentBlocks extends PoP_Module_Processor_BlocksBase
{
    public const MODULE_BLOCK_AUTHOR_CONTENT = 'block-author-content';
    public const MODULE_BLOCK_AUTHOR_SUMMARYCONTENT = 'block-author-summarycontent';
    public const MODULE_BLOCK_TAG_CONTENT = 'block-tag-content';
    public const MODULE_BLOCK_SINGLE_CONTENT = 'block-single-content';
    public const MODULE_BLOCK_PAGE_CONTENT = 'block-pageabout-content';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTHOR_CONTENT],
            [self::class, self::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT],
            [self::class, self::MODULE_BLOCK_TAG_CONTENT],
            [self::class, self::MODULE_BLOCK_SINGLE_CONTENT],
            [self::class, self::MODULE_BLOCK_PAGE_CONTENT],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            // The Page Content block uses whichever is the current page
            self::MODULE_BLOCK_PAGE_CONTENT => POP_ROUTE_DESCRIPTION,//\PoP\Root\App::getState('route'),
            self::MODULE_BLOCK_AUTHOR_CONTENT => POP_ROUTE_DESCRIPTION,
            self::MODULE_BLOCK_TAG_CONTENT => POP_ROUTE_DESCRIPTION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getDescriptionBottom(array $module, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT:
                $vars = ApplicationState::getVars();
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                return sprintf(
                    '<p class="text-center"><a href="%s">%s</a></p>',
                    $url,
                    TranslationAPIFacade::getInstance()->__('Go to Full Profile ', 'poptheme-wassup').'<i class="fa fa-fw fa-arrow-right"></i>'
                );
        }

        return parent::getDescriptionBottom($module, $props);
    }

    public function getTitle(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_AUTHOR_CONTENT:
            case self::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $userTypeAPI->getUserDisplayName($author);

            case self::MODULE_BLOCK_SINGLE_CONTENT:
            case self::MODULE_BLOCK_PAGE_CONTENT:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $customPostTypeAPI->getTitle($post_id);
        }

        return parent::getTitle($module, $props);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_PAGE_CONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SHARE];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_AUTHOR_CONTENT => [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::MODULE_DATALOAD_AUTHOR_CONTENT],
            self::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT => [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::MODULE_DATALOAD_AUTHOR_SUMMARYCONTENT],
            self::MODULE_BLOCK_TAG_CONTENT => [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::MODULE_DATALOAD_TAG_CONTENT],
            self::MODULE_BLOCK_SINGLE_CONTENT => [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::MODULE_DATALOAD_SINGLE_CONTENT],
            self::MODULE_BLOCK_PAGE_CONTENT => [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::MODULE_DATALOAD_PAGE_CONTENT],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TAG_CONTENT:
                $this->appendProp($module, $props, 'class', 'block-tag-content');
                break;

            case self::MODULE_BLOCK_PAGE_CONTENT:
                $this->appendProp($module, $props, 'class', 'block-singleabout-content');
                $inners = $this->getInnerSubmodules($module);
                foreach ($inners as $inner) {
                    $this->appendProp($inner, $props, 'class', 'col-xs-12');
                }
                break;

            case self::MODULE_BLOCK_SINGLE_CONTENT:
                $this->appendProp($module, $props, 'class', 'block-single-content');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLE_CONTENT:
                $vars = ApplicationState::getVars();

                // Also append the post_status, so we can hide the bottomsidebar for draft posts
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $this->appendProp($module, $props, 'runtime-class', $customPostTypeAPI->getCustomPostType($post_id) . '-' . $post_id);
                $this->appendProp($module, $props, 'runtime-class', $customPostTypeAPI->getStatus($post_id));
                break;
        }

        parent::initRequestProps($module, $props);
    }

    protected function getBlocksectionsClasses(array $module)
    {
        $ret = parent::getBlocksectionsClasses($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_PAGE_CONTENT:
                $ret['blocksection-inners'] = 'row row-item';
                break;
        }

        return $ret;
    }

    // function getNature(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_BLOCK_AUTHOR_CONTENT:
    //         case self::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT:

    //             return UserRouteNatures::USER;

    //         case self::MODULE_BLOCK_TAG_CONTENT:

    //             return TagRouteNatures::TAG;

    //         case self::MODULE_BLOCK_SINGLE_CONTENT:

    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }
}



