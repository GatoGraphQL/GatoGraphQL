<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait PoPTheme_Wassup_Module_Processor_PageSectionsTrait
{
    public function getExtraTemplateResources(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        // Add the extension templates
        $ret = parent::getExtraTemplateResources($component, $props);
        $ret['extensions'] = $ret['extensions'] ?? array();
        $ret['extensions'][] = [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS];
        return $ret;
    }

    protected function getFrameoptionsSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array_merge(
            $this->getFrametopoptionsSubcomponents($component),
            $this->getFramebottomoptionsSubcomponents($component)
        );
    }

    public function getFrametopoptionsSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function getFramebottomoptionsSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getFrameoptionsSubcomponents($component)
        );
    }

    public function getMutableonmodelConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($component, $props);

        if ($subcomponents = $this->getFrameoptionsSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['frameoptions'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $subcomponents
            );
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubcomponents($component) as $subcomponent) {
            $this->appendProp([$subcomponent], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubcomponents($component);
        $bottomframeoptions = $this->getFramebottomoptionsSubcomponents($component);
        foreach ($this->getFrameoptionsSubcomponents($component) as $subcomponent) {
            $this->appendProp([$subcomponent], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($subcomponent, $topframeoptions)) {
                $this->appendProp([$subcomponent], $props, 'class', 'top');
            } elseif (in_array($subcomponent, $bottomframeoptions)) {
                $this->appendProp([$subcomponent], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($component, $props);
    }
}
