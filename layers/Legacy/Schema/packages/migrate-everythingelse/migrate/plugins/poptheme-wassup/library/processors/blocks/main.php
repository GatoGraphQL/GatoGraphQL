<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_MainBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_HOME = 'block-home';
    public final const MODULE_BLOCK_404 = 'block-404';
    public final const MODULE_BLOCK_BACKGROUNDMENU = 'block-backgroundmenu';
    public final const MODULE_BLOCK_SINGLEPOST = 'block-singlepost';
    public final const MODULE_BLOCK_AUTHOR = 'block-author';
    public final const MODULE_BLOCK_AUTHORDESCRIPTION = 'block-authordescription';
    public final const MODULE_BLOCK_AUTHORSUMMARY = 'block-authorsummary';
    public final const MODULE_BLOCK_TAG = 'block-tag';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_HOME],
            [self::class, self::MODULE_BLOCK_404],
            [self::class, self::MODULE_BLOCK_BACKGROUNDMENU],
            [self::class, self::MODULE_BLOCK_SINGLEPOST],
            [self::class, self::MODULE_BLOCK_AUTHOR],
            [self::class, self::MODULE_BLOCK_AUTHORDESCRIPTION],
            [self::class, self::MODULE_BLOCK_AUTHORSUMMARY],
            [self::class, self::MODULE_BLOCK_TAG],
        );
    }

    protected function getControlgroupBottomSubmodule(array $module)
    {

        // Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLEPOST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
                $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
                if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
                    return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUSHARE];
                }
                break;

            case self::MODULE_BLOCK_AUTHOR:
            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUSHARE];

            case self::MODULE_BLOCK_TAG:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUSHARE];
        }

        return parent::getControlgroupBottomSubmodule($module);
    }

    protected function getBlocksectionsClasses(array $module)
    {
        $ret = parent::getBlocksectionsClasses($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_404:
            case self::MODULE_BLOCK_BACKGROUNDMENU:
                $ret['blocksection-extensions'] = 'row';
                break;

            case self::MODULE_BLOCK_SINGLEPOST:
            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
            case self::MODULE_BLOCK_AUTHORSUMMARY:
                // Make the background white, with the #6acada border
                $ret['blocksection-extensions'] = 'row row-item';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // switch ($module[1]) {

        //     case self::MODULE_BLOCK_SINGLEPOST:
        //     case self::MODULE_BLOCK_AUTHOR:
        //     case self::MODULE_BLOCK_AUTHORDESCRIPTION:
        //     case self::MODULE_BLOCK_AUTHORSUMMARY:
        //     case self::MODULE_BLOCK_TAG:

        //         $blocktarget = $this->get_activeblock_selector($module, $props);
        //         if ($controlgroup_top = $this->getControlgroupTopSubmodule($module)) {
        //             $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
        //         }
        //         if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($module)) {
        //             $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
        //         }
        //         break;
        // }

        switch ($module[1]) {
            case self::MODULE_BLOCK_HOME:
                $this->appendProp($module, $props, 'class', 'blockgroup-home');

                if (PoP_ApplicationProcessors_Utils::narrowBodyHome()) {
                     // Make it 564px max width
                    $this->appendProp($module, $props, 'class', 'narrow');
                }
                break;

            case self::MODULE_BLOCK_AUTHOR:
                $this->appendProp($module, $props, 'class', 'blockgroup-author');
                break;

            case self::MODULE_BLOCK_SINGLEPOST:
                $this->appendProp($module, $props, 'class', 'blockgroup-singlepost');
                break;

            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
                $this->appendProp($module, $props, 'class', 'blockgroup-author-description');
                break;

            case self::MODULE_BLOCK_TAG:
                $this->appendProp($module, $props, 'class', 'blockgroup-tag');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_BLOCK_404:
            case self::MODULE_BLOCK_BACKGROUNDMENU:
                foreach ($this->getSubComponentVariations($module) as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'col-xs-12 col-sm-4');
                }
                break;

            case self::MODULE_BLOCK_SINGLEPOST:
                foreach ($this->getSubComponentVariations($module) as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'col-xs-12');
                }

                // Hide the Title, but not for the Comments
                $this->setProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_SINGLE_CONTENT]], $props, 'title', '');
                break;

            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT]], $props, 'class', 'col-xs-12');
                $this->setProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT]], $props, 'title', '');
                break;

            case self::MODULE_BLOCK_AUTHORSUMMARY:
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT]], $props, 'title', '');
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT]], $props, 'class', 'col-xs-12');
                $this->appendProp([[PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST]], $props, 'class', 'col-xs-12');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_BLOCK_BACKGROUNDMENU:
            case self::MODULE_BLOCK_404:
                $submodule_descriptions = array(
                    [
                        'module' => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_SECTIONS],
                        'description' => TranslationAPIFacade::getInstance()->__('Content', 'poptheme-wassup'),
                    ],
                    [
                        'module' => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_MYSECTIONS],
                        'description' => TranslationAPIFacade::getInstance()->__('My Content', 'poptheme-wassup'),
                    ],
                    [
                        'module' => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_ADDCONTENT],
                        'description' => TranslationAPIFacade::getInstance()->__('Add Content', 'poptheme-wassup'),
                    ],
                );
                foreach ($submodule_descriptions as $submodule_description) {
                    $submodule = $submodule_description['submodule'];
                    $description = sprintf(
                        '<h4>%s</h4>',
                        sprintf(
                            TranslationAPIFacade::getInstance()->__('%s:', 'poptheme-wassup'),
                            $submodule_description['description']
                        )
                    );
                    $this->setProp([$submodule], $props, 'title', '');
                    $this->setProp([$submodule], $props, 'description', $description);
                }
                break;

            case self::MODULE_BLOCK_HOME:
            case self::MODULE_BLOCK_AUTHOR:
            case self::MODULE_BLOCK_TAG:

                // When loading the whole site, only the main content can have components retrieve params from the $_GET
                // This way, passing &limit=4 doesn't affect the results on the top widgets
                $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                $submodules = array_diff(
                    $this->getSubComponentVariations($module),
                    [
                        $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_MAINCONTENT)
                    ]
                );
                foreach ($submodules as $submodule) {
                    $this->setProp($submodule, $props, 'ignore-request-params', true);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLEPOST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($customPostTypeAPI->getStatus($post_id) !== Status::PUBLISHED) {
                    $this->setProp($module, $props, 'show-submenu', false);
                    $this->setProp($module, $props, 'show-controls-bottom', false);
                }
                break;
        }

        parent::initRequestProps($module, $props);
    }

    public function getSubmenuSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLEPOST:
                if ($submenu = PoP_Module_Processor_CustomSectionBlocksUtils::getSingleSubmenu()) {
                    return $submenu;
                }
                break;

            case self::MODULE_BLOCK_AUTHOR:
            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_AUTHOR];

            case self::MODULE_BLOCK_TAG:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_TAG];
        }

        return parent::getSubmenuSubmodule($module);
    }

    protected function getDescription(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_404:
                return sprintf(
                    '<p>%s</p>',
                    TranslationAPIFacade::getInstance()->__('Aiyoooo, it seems there is nothing here. Where else would you like to go?', 'poptheme-wassup')
                );
        }

        return parent::getDescription($module, $props);
    }

    public function getTitle(array $module, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_404:
                return TranslationAPIFacade::getInstance()->__('Oops, this page doesn\'t exist!', 'poptheme-wassup');

            case self::MODULE_BLOCK_SINGLEPOST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $customPostTypeAPI->getTitle($post_id);

            case self::MODULE_BLOCK_AUTHOR:
            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
            case self::MODULE_BLOCK_AUTHORSUMMARY:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $userTypeAPI->getUserDisplayName($author);

            case self::MODULE_BLOCK_TAG:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle(true, false);
        }

        return parent::getTitle($module, $props);
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_BLOCK_HOME:
                // Allow TPPDebate to override this
                if ($top_modules = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:modules:home_tops',
                    array(
                        [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::MODULE_GROUP_HOMETOP]
                    )
                )) {
                    $ret = array_merge(
                        $ret,
                        $top_modules
                    );
                }

                // Content module
                if ($content_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_MAINCONTENT)) {
                    $ret[] = $content_module;
                }
                break;

            case self::MODULE_BLOCK_AUTHOR:
                if ($top_modules = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:modules:author_tops',
                    array(
                        [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::MODULE_GROUP_AUTHORTOP],
                    )
                )) {
                    $ret = array_merge(
                        $ret,
                        $top_modules
                    );
                }

                // Content module
                if ($content_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_MAINCONTENT)) {
                    $ret[] = $content_module;
                }
                break;

            case self::MODULE_BLOCK_TAG:
                if ($top_modules = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:modules:tag_tops',
                    array(
                        [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::MODULE_GROUP_TAG_WIDGETAREA],
                    )
                )) {
                    $ret = array_merge(
                        $ret,
                        $top_modules
                    );
                }

                // Content module
                if ($content_module = $pop_module_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_MAINCONTENT)) {
                    $ret[] = $content_module;
                }
                break;

            case self::MODULE_BLOCK_404:
            case self::MODULE_BLOCK_BACKGROUNDMENU:
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_SECTIONS];
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_MYSECTIONS];
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_ADDCONTENT];
                break;

            case self::MODULE_BLOCK_AUTHORDESCRIPTION:
                $ret[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT];
                break;

            case self::MODULE_BLOCK_AUTHORSUMMARY:
                $ret[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_SUMMARYCONTENT];
                $ret[] = [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST];
                break;

            case self::MODULE_BLOCK_SINGLEPOST:
                // Allow TPPDebate to override this, adding the "What do you think about TPP" Create Block
                $modules = array(
                    [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_SINGLE_CONTENT],
                    [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::MODULE_DATALOAD_SINGLEINTERACTION_CONTENT],
                );
                $modules = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:modules:single',
                    $modules
                );
                $ret = array_merge(
                    $ret,
                    $modules
                );
                break;
        }

        return $ret;
    }
}


