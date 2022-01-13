<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_AlertsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_ALERT];
    }

    public function getLayoutSubmodules(array $module)
    {
        return array();
    }

    public function useCookie(array $module, array &$props)
    {
        return false;
    }

    public function showCloseButton(array $module, array &$props)
    {
        return true;
    }

    public function getClosebuttonTitle(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Dismiss', 'pop-coreprocessors');
    }

    public function getClosebuttonText(array $module, array &$props)
    {
        return 'x';
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->useCookie($module, $props)) {
            $this->addJsmethod($ret, 'cookies');
        }

        return $ret;
    }

    public function getAlertClass(array $module, array &$props)
    {
        return 'alert-info alert-sm';
    }

    public function getAlertBaseClass(array $module, array &$props)
    {
        return 'alert fade in';
    }

    public function getClosebuttonClass(array $module, array &$props)
    {
        return 'close';
    }

    public function addFeedbackobjectClass(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [ModuleUtils::class, 'getModuleOutputName'],
                $layouts
            );
        }

        if ($this->addFeedbackobjectClass($module, $props)) {
            $ret['add-feedbackobject-class'] = true;
        }

        if ($this->useCookie($module, $props)) {
            $ret['use-cookies'] = true;
        }

        if ($this->showCloseButton($module, $props)) {
            $ret['show-close-btn'] = true;
            $ret[GD_JS_TITLES] = array(
                'closebtn-title' => $this->getClosebuttonTitle($module, $props),
                'closebtn-text' => $this->getClosebuttonText($module, $props),
            );
            $ret[GD_JS_CLASSES] = array(
                'close-btn' => $this->getClosebuttonClass($module, $props),
            );
        }

        if ($description = $this->getProp($module, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($class = $this->getAlertBaseClass($module, $props)) {
            $this->appendProp($module, $props, 'class', $class);
        }

        // Allow this value to be set from above
        if ($class = $this->getProp($module, $props, 'alert-class')) {
            $this->appendProp($module, $props, 'class', $class);
        }
        // Otherwise, use the default value
        elseif ($class = $this->getAlertClass($module, $props)) {
            $this->appendProp($module, $props, 'class', $class);
        }

        if ($this->useCookie($module, $props)) {
            $this->appendProp($module, $props, 'class', 'cookie hidden');
        }
        parent::initModelProps($module, $props);
    }
}
