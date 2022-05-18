<?php

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const MODULE_FORMINNER_CONTENTPOSTLINK = 'forminner-contentpostlink';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_CONTENTPOSTLINK],
        );
    }

    protected function isLink(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_CONTENTPOSTLINK:
                return true;
        }

        return parent::isLink($component);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_CONTENTPOSTLINK:
                return array_merge(
                    $ret,
                    array(
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE],
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE],
                    )
                );
        }

        return parent::getComponentSubmodules($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_CONTENTPOSTLINK:
                $rightside = [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE];
                $leftside = [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE];

                if (!($form_left_class = $this->getProp($component, $props, 'form-left-class')/*$this->get_general_prop($props, 'form-left-class')*/)) {
                    $form_left_class = 'col-sm-8';
                }
                if (!($form_right_class = $this->getProp($component, $props, 'form-right-class')/*$this->get_general_prop($props, 'form-right-class')*/)) {
                    $form_right_class = 'col-sm-4';
                }
                $this->appendProp($leftside, $props, 'class', $form_left_class);
                $this->appendProp($rightside, $props, 'class', $form_right_class);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



