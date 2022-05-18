<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_AlertsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_ALERT];
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        return array();
    }

    public function useCookie(array $componentVariation, array &$props)
    {
        return false;
    }

    public function showCloseButton(array $componentVariation, array &$props)
    {
        return true;
    }

    public function getClosebuttonTitle(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Dismiss', 'pop-coreprocessors');
    }

    public function getClosebuttonText(array $componentVariation, array &$props)
    {
        return 'x';
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->useCookie($componentVariation, $props)) {
            $this->addJsmethod($ret, 'cookies');
        }

        return $ret;
    }

    public function getAlertClass(array $componentVariation, array &$props)
    {
        return 'alert-info alert-sm';
    }

    public function getAlertBaseClass(array $componentVariation, array &$props)
    {
        return 'alert fade in';
    }

    public function getClosebuttonClass(array $componentVariation, array &$props)
    {
        return 'close';
    }

    public function addFeedbackobjectClass(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }

        if ($this->addFeedbackobjectClass($componentVariation, $props)) {
            $ret['add-feedbackobject-class'] = true;
        }

        if ($this->useCookie($componentVariation, $props)) {
            $ret['use-cookies'] = true;
        }

        if ($this->showCloseButton($componentVariation, $props)) {
            $ret['show-close-btn'] = true;
            $ret[GD_JS_TITLES] = array(
                'closebtn-title' => $this->getClosebuttonTitle($componentVariation, $props),
                'closebtn-text' => $this->getClosebuttonText($componentVariation, $props),
            );
            $ret[GD_JS_CLASSES] = array(
                'close-btn' => $this->getClosebuttonClass($componentVariation, $props),
            );
        }

        if ($description = $this->getProp($componentVariation, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($class = $this->getAlertBaseClass($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', $class);
        }

        // Allow this value to be set from above
        if ($class = $this->getProp($componentVariation, $props, 'alert-class')) {
            $this->appendProp($componentVariation, $props, 'class', $class);
        }
        // Otherwise, use the default value
        elseif ($class = $this->getAlertClass($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', $class);
        }

        if ($this->useCookie($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', 'cookie hidden');
        }
        parent::initModelProps($componentVariation, $props);
    }
}
