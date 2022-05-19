<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_UserPostInteractionLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        if ($user_avatar = $this->getLoggedinUseravatarSubcomponent($component)) {
            $ret[] = $user_avatar;
        }
        return $ret;
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_USERPOSTINTERACTION];
    }

    public function getLayoutSubcomponents(array $component)
    {
        return array();
    }

    public function getLoggedinUseravatarSubcomponent()
    {
        if (defined('POP_USERAVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_LoggedInUserAvatars::class, PoP_Module_Processor_LoggedInUserAvatars::COMPONENT_LAYOUT_LOGGEDINUSERAVATAR];
        }

        return null;
    }

    public function getStyleClass(array $component, array &$props)
    {
        return '';
    }

    public function addUseravatar(array $component, array &$props)
    {

        // If the plugin to create avatar is defined, then enable it
        return PoP_Application_ConfigurationUtils::useUseravatar();
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($user_avatar = $this->getLoggedinUseravatarSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['useravatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($user_avatar);
        }

        $ret['add-useravatar'] = $this->addUseravatar($component, $props);

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->addUseravatar($component, $props)) {
            $this->addJsmethod($ret, 'loadLoggedInUserAvatar', 'useravatar');
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'frame-addcomment');

        // Add the style for the frame
        if ($classs = $this->getStyleClass($component, $props)) {
            $this->appendProp($component, $props, 'class', $classs);
        }

        parent::initModelProps($component, $props);
    }
}
