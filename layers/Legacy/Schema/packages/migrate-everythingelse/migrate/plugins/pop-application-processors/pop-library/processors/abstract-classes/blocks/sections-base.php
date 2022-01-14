<?php
use PoP\ComponentModel\Facades\ModulePath\ModulePathManagerFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\ModuleProcessors\DataloadingModuleInterface;
use PoP\ComponentModel\ModuleProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Routing\RequestNature;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\Routing\RequestNature as UserRequestNature;

abstract class PoP_Module_Processor_SectionBlocksBase extends PoP_Module_Processor_BlocksBase implements FormattableModuleInterface
{
    // public function getNature(array $module)
    // {
    //     if ($inner = $this->getInnerSubmodule($module)) {
    //         $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
    //         $processor = $moduleprocessor_manager->getProcessor($inner);
    //         return $processor->getNature($inner);
    //     }

    //     return parent::getNature($module);
    // }

    public function getSubmenuSubmodule(array $module)
    {

        // Add only if the current nature is the one expected by the block
        // if (\PoP\Root\App::getState('nature') == $this->getNature($module)) {
        switch (\PoP\Root\App::getState('nature')) {
            case UserRequestNature::USER:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_AUTHOR];

            case TagRequestNature::TAG:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_TAG];

            case CustomPostRequestNature::CUSTOMPOST:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleSubmenu();
        }
        // }

        return parent::getSubmenuSubmodule($module);
    }

    public function getTitle(array $module, array &$props)
    {

        // Add only if the current nature is the one expected by the block
        // if (\PoP\Root\App::getState('nature') == $this->getNature($module)) {
        switch (\PoP\Root\App::getState('nature')) {
            case UserRequestNature::USER:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getAuthorTitle();

            case TagRequestNature::TAG:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle();

            case CustomPostRequestNature::CUSTOMPOST:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleTitle();
        }
        // }

        return parent::getTitle($module, $props);
    }

    protected function getTitleLink(array $module, array &$props)
    {
        if ($route = $this->getRelevantRoute($module, $props)) {
            $cmsService = CMSServiceFacade::getInstance();
            $userTypeAPI = UserTypeAPIFacade::getInstance();
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
            switch (\PoP\Root\App::getState('nature')) {
                case UserRequestNature::USER:
                    $url = $userTypeAPI->getUserURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                    return RequestUtils::addRoute($url, $route);

                case TagRequestNature::TAG:
                    $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                    return RequestUtils::addRoute($url, $route);

                case PageRequestNature::PAGE:
                case CustomPostRequestNature::CUSTOMPOST:
                    $url = $customPostTypeAPI->getPermalink(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                    return RequestUtils::addRoute($url, $route);

                case RequestNature::HOME:
                    $url = $cmsService->getHomeURL();
                    return RequestUtils::addRoute($url, $route);
            }
        }

        return parent::getTitleLink($module, $props);
    }

    public function getFormat(array $module): ?string
    {
        if ($inner = $this->getInnerSubmodule($module)) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            $processor = $moduleprocessor_manager->getProcessor($inner);
            if ($processor instanceof FormattableModuleInterface) {
                return $processor->getFormat($inner);
            }
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        // If the inner module is a DataloadingModule, then transfer dataloading properties to its contained module
        if ($inner_module = $this->getInnerSubmodule($module)) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            if ($moduleprocessor_manager->getProcessor($inner_module) instanceof DataloadingModuleInterface) {

                $skip_data_load = $this->getProp($module, $props, 'skip-data-load');
                if (!is_null($skip_data_load)) {
                    $this->setProp([$inner_module], $props, 'skip-data-load', $skip_data_load);
                }
                $lazy_load = $this->getProp($module, $props, 'lazy-load');
                if (!is_null($lazy_load)) {
                    $this->setProp([$inner_module], $props, 'lazy-load', $lazy_load);
                }
            }
        }

        if ($format = $this->getFormat($module)) {
            $classes = array(
                POP_FORMAT_SIMPLEVIEW => 'feed',
                POP_FORMAT_FULLVIEW => 'feed',
                POP_FORMAT_DETAILS => 'feed',
                POP_FORMAT_THUMBNAIL => 'feed',
                POP_FORMAT_LIST => 'feed',

                // Needed for restraining to 600px together with class pop-outerblock
                POP_FORMAT_TABLE => 'tableblock',
                POP_FORMAT_CAROUSEL => 'block-carousel',
                POP_FORMAT_CAROUSELCONTENT => 'block-carousel',
                POP_FORMAT_CALENDAR => 'calendar pop-block-calendar',
                POP_FORMAT_MAP => 'map pop-block-map',
                POP_FORMAT_CALENDARMAP => 'map pop-block-map pop-block-calendarmap pop-block-calendar',
            );
            if ($class = $classes[$format] ?? null) {
                $this->appendProp($module, $props, 'class', $class);
            }

            $resourceloaders = array(
                POP_FORMAT_CAROUSEL => 'block-carousel',
                POP_FORMAT_CAROUSELCONTENT => 'block-carousel',
                POP_FORMAT_CALENDAR => 'calendar',
                POP_FORMAT_MAP => 'map',
                POP_FORMAT_CALENDARMAP => 'calendarmap',
            );
            if ($resourceloader = $resourceloaders[$format] ?? null) {
                   // Artificial property added to identify the template when adding module-resources
                $this->setProp($module, $props, 'resourceloader', $resourceloader);
            }
        }

        if ($sectionfilter = $this->getSectionfilterModule($module)) {
            // Class needed to push the "Loading" status a tiny bit down, so it doesn't show on top of the sectionfilter
            $this->appendProp($module, $props, 'class', 'withsectionfilter');

            // Check if the filter needs to be hidden (eg: GetPoP homepage)
            if ($this->getProp($module, $props, 'hide-sectionfilter')) {
                $this->appendProp($sectionfilter, $props, 'class', 'hidden');
            }
        }

        parent::initModelProps($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        if ($sectionfilter_module = $this->getSectionfilterModule($module)) {
            $ret[] = $sectionfilter_module;
        }

        if ($inner_module = $this->getInnerSubmodule($module)) {
            $ret[] = $inner_module;
        }

        return $ret;
    }

    protected function getSectionfilterModule(array $module)
    {
        return null;
    }

    protected function getInnerSubmodule(array $module)
    {
        return null;
    }

    public function getDataFeedbackInterreferencedModulepath(array $module, array &$props): ?array
    {

        // If the inner module is a DataloadingModule, then calculate the datafeedback of this module
        // based on the results from the inner dataloading module. Then, can calculate "do-not-render-if-no-results"
        if ($inner = $this->getInnerSubmodule($module)) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            $processor = $moduleprocessor_manager->getProcessor($inner);
            if ($processor instanceof DataloadingModuleInterface) {
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $module_propagation_current_path = $module_path_manager->getPropagationCurrentPath();
                $module_propagation_current_path[] = $module;
                $module_propagation_current_path[] = $inner;
                return $module_propagation_current_path;
            }
        }

        return parent::getDataFeedbackInterreferencedModulepath($module, $props);
    }
}
