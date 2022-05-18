<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_LocationPostLinksCreation_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE = 'multicomponent-form-locationpostlink-rightside';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $status = GD_CreateUpdate_Utils::moderate() ?
            [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
            [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE:
                $details = array(
                    self::MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE => [PoP_LocationPostLinks_Module_Processor_FormWidgets::class, PoP_LocationPostLinks_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS],
                );
                $ret[] = $details[$module[1]];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_METAINFORMATION];
                $ret[] = $status;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE:
                if (!($classs = $this->getProp($module, $props, 'forminput-publish-class')/*$this->get_general_prop($props, 'forminput-publish-class')*/)) {
                    $classs = 'alert alert-info';
                }
                $status = GD_CreateUpdate_Utils::moderate() ?
                    [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
                    [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $this->appendProp($status, $props, 'class', $classs);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



