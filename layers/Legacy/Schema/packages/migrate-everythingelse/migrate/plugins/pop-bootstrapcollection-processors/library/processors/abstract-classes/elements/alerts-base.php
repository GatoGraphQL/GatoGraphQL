<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_AlertsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_ALERT];
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function useCookie(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function showCloseButton(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }

    public function getClosebuttonTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Dismiss', 'pop-coreprocessors');
    }

    public function getClosebuttonText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'x';
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->useCookie($component, $props)) {
            $this->addJsmethod($ret, 'cookies');
        }

        return $ret;
    }

    public function getAlertClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'alert-info alert-sm';
    }

    public function getAlertBaseClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'alert fade in';
    }

    public function getClosebuttonClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'close';
    }

    public function addFeedbackobjectClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $layouts
            );
        }

        if ($this->addFeedbackobjectClass($component, $props)) {
            $ret['add-feedbackobject-class'] = true;
        }

        if ($this->useCookie($component, $props)) {
            $ret['use-cookies'] = true;
        }

        if ($this->showCloseButton($component, $props)) {
            $ret['show-close-btn'] = true;
            $ret[GD_JS_TITLES] = array(
                'closebtn-title' => $this->getClosebuttonTitle($component, $props),
                'closebtn-text' => $this->getClosebuttonText($component, $props),
            );
            $ret[GD_JS_CLASSES] = array(
                'close-btn' => $this->getClosebuttonClass($component, $props),
            );
        }

        if ($description = $this->getProp($component, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($class = $this->getAlertBaseClass($component, $props)) {
            $this->appendProp($component, $props, 'class', $class);
        }

        // Allow this value to be set from above
        if ($class = $this->getProp($component, $props, 'alert-class')) {
            $this->appendProp($component, $props, 'class', $class);
        }
        // Otherwise, use the default value
        elseif ($class = $this->getAlertClass($component, $props)) {
            $this->appendProp($component, $props, 'class', $class);
        }

        if ($this->useCookie($component, $props)) {
            $this->appendProp($component, $props, 'class', 'cookie hidden');
        }
        parent::initModelProps($component, $props);
    }
}
