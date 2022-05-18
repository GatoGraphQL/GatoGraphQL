<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_UserPostInteractionLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        if ($user_avatar = $this->getLoggedinUseravatarSubmodule($componentVariation)) {
            $ret[] = $user_avatar;
        }
        return $ret;
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_USERPOSTINTERACTION];
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getLoggedinUseravatarSubmodule()
    {
        if (defined('POP_USERAVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_LoggedInUserAvatars::class, PoP_Module_Processor_LoggedInUserAvatars::MODULE_LAYOUT_LOGGEDINUSERAVATAR];
        }

        return null;
    }

    public function getStyleClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function addUseravatar(array $componentVariation, array &$props)
    {

        // If the plugin to create avatar is defined, then enable it
        return PoP_Application_ConfigurationUtils::useUseravatar();
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($user_avatar = $this->getLoggedinUseravatarSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['useravatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($user_avatar);
        }

        $ret['add-useravatar'] = $this->addUseravatar($componentVariation, $props);

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->addUseravatar($componentVariation, $props)) {
            $this->addJsmethod($ret, 'loadLoggedInUserAvatar', 'useravatar');
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'frame-addcomment');

        // Add the style for the frame
        if ($classs = $this->getStyleClass($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', $classs);
        }

        parent::initModelProps($componentVariation, $props);
    }
}
