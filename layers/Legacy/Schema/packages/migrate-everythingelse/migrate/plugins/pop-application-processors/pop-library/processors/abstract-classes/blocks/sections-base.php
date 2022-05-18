<?php
use PoP\ComponentModel\Facades\ModulePath\ModulePathManagerFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\ComponentProcessors\DataloadingModuleInterface;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

abstract class PoP_Module_Processor_SectionBlocksBase extends PoP_Module_Processor_BlocksBase implements FormattableModuleInterface
{
    // public function getNature(array $componentVariation)
    // {
    //     if ($inner = $this->getInnerSubmodule($componentVariation)) {
    //         $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    //         $processor = $componentprocessor_manager->getProcessor($inner);
    //         return $processor->getNature($inner);
    //     }

    //     return parent::getNature($componentVariation);
    // }

    public function getSubmenuSubmodule(array $componentVariation)
    {

        // Add only if the current nature is the one expected by the block
        // if (\PoP\Root\App::getState('nature') == $this->getNature($componentVariation)) {
        switch (\PoP\Root\App::getState('nature')) {
            case UserRequestNature::USER:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_AUTHOR];

            case TagRequestNature::TAG:
                return [PoP_Module_Processor_CustomSubMenus::class, PoP_Module_Processor_CustomSubMenus::MODULE_SUBMENU_TAG];

            case CustomPostRequestNature::CUSTOMPOST:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleSubmenu();
        }
        // }

        return parent::getSubmenuSubmodule($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {

        // Add only if the current nature is the one expected by the block
        // if (\PoP\Root\App::getState('nature') == $this->getNature($componentVariation)) {
        switch (\PoP\Root\App::getState('nature')) {
            case UserRequestNature::USER:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getAuthorTitle();

            case TagRequestNature::TAG:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle();

            case CustomPostRequestNature::CUSTOMPOST:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getSingleTitle();
        }
        // }

        return parent::getTitle($componentVariation, $props);
    }

    protected function getTitleLink(array $componentVariation, array &$props)
    {
        if ($route = $this->getRelevantRoute($componentVariation, $props)) {
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

        return parent::getTitleLink($componentVariation, $props);
    }

    public function getFormat(array $componentVariation): ?string
    {
        if ($inner = $this->getInnerSubmodule($componentVariation)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            $processor = $componentprocessor_manager->getProcessor($inner);
            if ($processor instanceof FormattableModuleInterface) {
                return $processor->getFormat($inner);
            }
        }

        return null;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        // If the inner module is a DataloadingModule, then transfer dataloading properties to its contained module
        if ($inner_module = $this->getInnerSubmodule($componentVariation)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            if ($componentprocessor_manager->getProcessor($inner_module) instanceof DataloadingModuleInterface) {

                $skip_data_load = $this->getProp($componentVariation, $props, 'skip-data-load');
                if (!is_null($skip_data_load)) {
                    $this->setProp([$inner_module], $props, 'skip-data-load', $skip_data_load);
                }
                $lazy_load = $this->getProp($componentVariation, $props, 'lazy-load');
                if (!is_null($lazy_load)) {
                    $this->setProp([$inner_module], $props, 'lazy-load', $lazy_load);
                }
            }
        }

        if ($format = $this->getFormat($componentVariation)) {
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
                $this->appendProp($componentVariation, $props, 'class', $class);
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
                $this->setProp($componentVariation, $props, 'resourceloader', $resourceloader);
            }
        }

        if ($sectionfilter = $this->getSectionfilterModule($componentVariation)) {
            // Class needed to push the "Loading" status a tiny bit down, so it doesn't show on top of the sectionfilter
            $this->appendProp($componentVariation, $props, 'class', 'withsectionfilter');

            // Check if the filter needs to be hidden (eg: GetPoP homepage)
            if ($this->getProp($componentVariation, $props, 'hide-sectionfilter')) {
                $this->appendProp($sectionfilter, $props, 'class', 'hidden');
            }
        }

        parent::initModelProps($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        if ($sectionfilter_module = $this->getSectionfilterModule($componentVariation)) {
            $ret[] = $sectionfilter_module;
        }

        if ($inner_module = $this->getInnerSubmodule($componentVariation)) {
            $ret[] = $inner_module;
        }

        return $ret;
    }

    protected function getSectionfilterModule(array $componentVariation)
    {
        return null;
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getDataFeedbackInterreferencedComponentVariationPath(array $componentVariation, array &$props): ?array
    {

        // If the inner module is a DataloadingModule, then calculate the datafeedback of this module
        // based on the results from the inner dataloading module. Then, can calculate "do-not-render-if-no-results"
        if ($inner = $this->getInnerSubmodule($componentVariation)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            $processor = $componentprocessor_manager->getProcessor($inner);
            if ($processor instanceof DataloadingModuleInterface) {
                $module_path_manager = ModulePathManagerFacade::getInstance();
                $module_propagation_current_path = $module_path_manager->getPropagationCurrentPath();
                $module_propagation_current_path[] = $componentVariation;
                $module_propagation_current_path[] = $inner;
                return $module_propagation_current_path;
            }
        }

        return parent::getDataFeedbackInterreferencedComponentVariationPath($componentVariation, $props);
    }
}
