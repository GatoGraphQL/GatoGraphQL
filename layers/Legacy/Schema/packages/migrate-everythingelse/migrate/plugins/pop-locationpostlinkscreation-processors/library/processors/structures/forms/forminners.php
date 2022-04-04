<?php

class PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const MODULE_FORMINNER_LOCATIONPOSTLINK = 'forminner-locationpostlink';

    public function getModulesToProcess(): array
    {
        return array(
            [GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners::class, GD_Custom_EM_Module_Processor_CreateUpdatePostFormInners::MODULE_FORMINNER_LOCATIONPOSTLINK],
        );
    }

    protected function isLink(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_LOCATIONPOSTLINK:
                return true;
        }

        return parent::isLink($module);
    }

    protected function volunteering(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_LOCATIONPOSTLINK:
                return true;
        }

        return parent::volunteering($module);
    }
    protected function getLocationsInput(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_LOCATIONPOSTLINK:
                return [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP];
        }

        return parent::getLocationsInput($module);
    }

    public function getLayoutSubmodules(array $module)
    {

        // Comment Leo 03/04/2015: IMPORTANT!
        // For the _wpnonce and the pid, get the value from the queryhandler when editing
        // Why? because otherwise, if first loading an Edit Discussion (eg: http://m3l.localhost/edit-discussion/?_wpnonce=e88efa07c5&pid=17887)
        // being the user logged out and only then he log in, the refetchBlock doesn't work because it doesn't have the pid/_wpnonce values
        // Adding it through QueryInputOutputHandler EditPost allows us to have it there always, even if the post was not loaded since the user has no access to it
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FORMINNER_LOCATIONPOSTLINK:
                return array_merge(
                    $ret,
                    array(
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_LINK_LEFTSIDE],
                        [PoP_LocationPostLinksCreation_Module_Processor_FormMultipleComponents::class, PoP_LocationPostLinksCreation_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE],
                    )
                );
        }

        return parent::getComponentSubmodules($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_LOCATIONPOSTLINK:
                // Make it into left/right columns
                $rightside_module = [PoP_LocationPostLinksCreation_Module_Processor_FormMultipleComponents::class, PoP_LocationPostLinksCreation_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE];
                $leftside_module = [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORM_LINK_LEFTSIDE];

                // Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
                if (!($form_left_class = $this->getProp($module, $props, 'form-left-class')/*$this->get_general_prop($props, 'form-left-class')*/)) {
                    $form_left_class = 'col-sm-8';
                }
                if (!($form_right_class = $this->getProp($module, $props, 'form-right-class')/*$this->get_general_prop($props, 'form-right-class')*/)) {
                    $form_right_class = 'col-sm-4';
                }
                $this->appendProp($leftside_module, $props, 'class', $form_left_class);
                $this->appendProp($rightside_module, $props, 'class', $form_right_class);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



