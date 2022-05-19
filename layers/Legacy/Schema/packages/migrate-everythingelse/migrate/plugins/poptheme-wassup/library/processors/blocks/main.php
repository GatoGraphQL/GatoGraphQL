<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_MainBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_HOME = 'block-home';
    public final const COMPONENT_BLOCK_404 = 'block-404';
    public final const COMPONENT_BLOCK_BACKGROUNDMENU = 'block-backgroundmenu';
    public final const COMPONENT_BLOCK_SINGLEPOST = 'block-singlepost';
    public final const COMPONENT_BLOCK_AUTHOR = 'block-author';
    public final const COMPONENT_BLOCK_AUTHORDESCRIPTION = 'block-authordescription';
    public final const COMPONENT_BLOCK_AUTHORSUMMARY = 'block-authorsummary';
    public final const COMPONENT_BLOCK_TAG = 'block-tag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_HOME],
            [self::class, self::COMPONENT_BLOCK_404],
            [self::class, self::COMPONENT_BLOCK_BACKGROUNDMENU],
            [self::class, self::COMPONENT_BLOCK_SINGLEPOST],
            [self::class, self::COMPONENT_BLOCK_AUTHOR],
            [self::class, self::COMPONENT_BLOCK_AUTHORDESCRIPTION],
            [self::class, self::COMPONENT_BLOCK_AUTHORSUMMARY],
            [self::class, self::COMPONENT_BLOCK_TAG],
        );
    }

    protected function getControlgroupBottomSubcomponent(array $component)
    {

        // Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SINGLEPOST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
                $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
                if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
                    return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUSHARE];
                }
                break;

            case self::COMPONENT_BLOCK_AUTHOR:
            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUSHARE];

            case self::COMPONENT_BLOCK_TAG:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUSHARE];
        }

        return parent::getControlgroupBottomSubcomponent($component);
    }

    protected function getBlocksectionsClasses(array $component)
    {
        $ret = parent::getBlocksectionsClasses($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_404:
            case self::COMPONENT_BLOCK_BACKGROUNDMENU:
                $ret['blocksection-extensions'] = 'row';
                break;

            case self::COMPONENT_BLOCK_SINGLEPOST:
            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
            case self::COMPONENT_BLOCK_AUTHORSUMMARY:
                // Make the background white, with the #6acada border
                $ret['blocksection-extensions'] = 'row row-item';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // switch ($component[1]) {

        //     case self::COMPONENT_BLOCK_SINGLEPOST:
        //     case self::COMPONENT_BLOCK_AUTHOR:
        //     case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
        //     case self::COMPONENT_BLOCK_AUTHORSUMMARY:
        //     case self::COMPONENT_BLOCK_TAG:

        //         $blocktarget = $this->get_activeblock_selector($component, $props);
        //         if ($controlgroup_top = $this->getControlgroupTopSubcomponent($component)) {
        //             $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
        //         }
        //         if ($controlgroup_bottom = $this->getControlgroupBottomSubcomponent($component)) {
        //             $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
        //         }
        //         break;
        // }

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_HOME:
                $this->appendProp($component, $props, 'class', 'blockgroup-home');

                if (PoP_ApplicationProcessors_Utils::narrowBodyHome()) {
                     // Make it 564px max width
                    $this->appendProp($component, $props, 'class', 'narrow');
                }
                break;

            case self::COMPONENT_BLOCK_AUTHOR:
                $this->appendProp($component, $props, 'class', 'blockgroup-author');
                break;

            case self::COMPONENT_BLOCK_SINGLEPOST:
                $this->appendProp($component, $props, 'class', 'blockgroup-singlepost');
                break;

            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
                $this->appendProp($component, $props, 'class', 'blockgroup-author-description');
                break;

            case self::COMPONENT_BLOCK_TAG:
                $this->appendProp($component, $props, 'class', 'blockgroup-tag');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_404:
            case self::COMPONENT_BLOCK_BACKGROUNDMENU:
                foreach ($this->getSubcomponents($component) as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'col-xs-12 col-sm-4');
                }
                break;

            case self::COMPONENT_BLOCK_SINGLEPOST:
                foreach ($this->getSubcomponents($component) as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'col-xs-12');
                }

                // Hide the Title, but not for the Comments
                $this->setProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_SINGLE_CONTENT]], $props, 'title', '');
                break;

            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT]], $props, 'class', 'col-xs-12');
                $this->setProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT]], $props, 'title', '');
                break;

            case self::COMPONENT_BLOCK_AUTHORSUMMARY:
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_SUMMARYCONTENT]], $props, 'title', '');
                $this->appendProp([[PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_SUMMARYCONTENT]], $props, 'class', 'col-xs-12');
                $this->appendProp([[PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST]], $props, 'class', 'col-xs-12');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_BACKGROUNDMENU:
            case self::COMPONENT_BLOCK_404:
                $subcomponent_descriptions = array(
                    [
                        'component' => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS],
                        'description' => TranslationAPIFacade::getInstance()->__('Content', 'poptheme-wassup'),
                    ],
                    [
                        'component' => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS],
                        'description' => TranslationAPIFacade::getInstance()->__('My Content', 'poptheme-wassup'),
                    ],
                    [
                        'component' => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT],
                        'description' => TranslationAPIFacade::getInstance()->__('Add Content', 'poptheme-wassup'),
                    ],
                );
                foreach ($subcomponent_descriptions as $subcomponent_description) {
                    $subComponent = $subcomponent_description['subcomponent'];
                    $description = sprintf(
                        '<h4>%s</h4>',
                        sprintf(
                            TranslationAPIFacade::getInstance()->__('%s:', 'poptheme-wassup'),
                            $subcomponent_description['description']
                        )
                    );
                    $this->setProp([$subComponent], $props, 'title', '');
                    $this->setProp([$subComponent], $props, 'description', $description);
                }
                break;

            case self::COMPONENT_BLOCK_HOME:
            case self::COMPONENT_BLOCK_AUTHOR:
            case self::COMPONENT_BLOCK_TAG:

                // When loading the whole site, only the main content can have components retrieve params from the $_GET
                // This way, passing &limit=4 doesn't affect the results on the top widgets
                $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
                $subComponents = array_diff(
                    $this->getSubcomponents($component),
                    [
                        $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_MAINCONTENT)
                    ]
                );
                foreach ($subComponents as $subComponent) {
                    $this->setProp($subComponent, $props, 'ignore-request-params', true);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SINGLEPOST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($customPostTypeAPI->getStatus($post_id) !== Status::PUBLISHED) {
                    $this->setProp($component, $props, 'show-submenu', false);
                    $this->setProp($component, $props, 'show-controls-bottom', false);
                }
                break;
        }

        parent::initRequestProps($component, $props);
    }

    public function getSubmenuSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SINGLEPOST:
                if ($submenu = PoP_Module_Processor_CustomSectionBlocksUtils::getSingleSubmenu()) {
                    return $submenu;
                }
                break;

            case self::COMPONENT_BLOCK_AUTHOR:
            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::COMPONENT_SUBMENU_AUTHOR];

            case self::COMPONENT_BLOCK_TAG:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::COMPONENT_SUBMENU_TAG];
        }

        return parent::getSubmenuSubcomponent($component);
    }

    protected function getDescription(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_404:
                return sprintf(
                    '<p>%s</p>',
                    TranslationAPIFacade::getInstance()->__('Aiyoooo, it seems there is nothing here. Where else would you like to go?', 'poptheme-wassup')
                );
        }

        return parent::getDescription($component, $props);
    }

    public function getTitle(array $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_404:
                return TranslationAPIFacade::getInstance()->__('Oops, this page doesn\'t exist!', 'poptheme-wassup');

            case self::COMPONENT_BLOCK_SINGLEPOST:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $customPostTypeAPI->getTitle($post_id);

            case self::COMPONENT_BLOCK_AUTHOR:
            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
            case self::COMPONENT_BLOCK_AUTHORSUMMARY:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $userTypeAPI->getUserDisplayName($author);

            case self::COMPONENT_BLOCK_TAG:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle(true, false);
        }

        return parent::getTitle($component, $props);
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_HOME:
                // Allow TPPDebate to override this
                if ($top_components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:components:home_tops',
                    array(
                        [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_HOMETOP]
                    )
                )) {
                    $ret = array_merge(
                        $ret,
                        $top_components
                    );
                }

                // Content module
                if ($content_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_MAINCONTENT)) {
                    $ret[] = $content_component;
                }
                break;

            case self::COMPONENT_BLOCK_AUTHOR:
                if ($top_components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:components:author_tops',
                    array(
                        [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_AUTHORTOP],
                    )
                )) {
                    $ret = array_merge(
                        $ret,
                        $top_components
                    );
                }

                // Content module
                if ($content_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_MAINCONTENT)) {
                    $ret[] = $content_component;
                }
                break;

            case self::COMPONENT_BLOCK_TAG:
                if ($top_components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:components:tag_tops',
                    array(
                        [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_TAG_WIDGETAREA],
                    )
                )) {
                    $ret = array_merge(
                        $ret,
                        $top_components
                    );
                }

                // Content module
                if ($content_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_MAINCONTENT)) {
                    $ret[] = $content_component;
                }
                break;

            case self::COMPONENT_BLOCK_404:
            case self::COMPONENT_BLOCK_BACKGROUNDMENU:
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS];
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS];
                $ret[] = [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT];
                break;

            case self::COMPONENT_BLOCK_AUTHORDESCRIPTION:
                $ret[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_CONTENT];
                break;

            case self::COMPONENT_BLOCK_AUTHORSUMMARY:
                $ret[] = [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_AUTHOR_SUMMARYCONTENT];
                $ret[] = [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST];
                break;

            case self::COMPONENT_BLOCK_SINGLEPOST:
                // Allow TPPDebate to override this, adding the "What do you think about TPP" Create Block
                $components = array(
                    [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_SINGLE_CONTENT],
                    [PoP_Module_Processor_CustomContentDataloads::class, PoP_Module_Processor_CustomContentDataloads::COMPONENT_DATALOAD_SINGLEINTERACTION_CONTENT],
                );
                $components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_MainGroups:components:single',
                    $components
                );
                $ret = array_merge(
                    $ret,
                    $components
                );
                break;
        }

        return $ret;
    }
}


