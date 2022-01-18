<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

abstract class PoP_Module_Processor_UserPostInteractionLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        if ($user_avatar = $this->getLoggedinUseravatarSubmodule($module)) {
            $ret[] = $user_avatar;
        }
        return $ret;
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_USERPOSTINTERACTION];
    }

    public function getLayoutSubmodules(array $module)
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

    public function getStyleClass(array $module, array &$props)
    {
        return '';
    }

    public function addUseravatar(array $module, array &$props)
    {

        // If the plugin to create avatar is defined, then enable it
        return PoP_Application_ConfigurationUtils::useUseravatar();
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($user_avatar = $this->getLoggedinUseravatarSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['useravatar'] = ModuleUtils::getModuleOutputName($user_avatar);
        }

        $ret['add-useravatar'] = $this->addUseravatar($module, $props);

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->addUseravatar($module, $props)) {
            $this->addJsmethod($ret, 'loadLoggedInUserAvatar', 'useravatar');
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'frame-addcomment');

        // Add the style for the frame
        if ($classs = $this->getStyleClass($module, $props)) {
            $this->appendProp($module, $props, 'class', $classs);
        }

        parent::initModelProps($module, $props);
    }
}
