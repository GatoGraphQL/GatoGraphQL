<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Module_Processor_InstantaneousSimpleFilterInners extends PoP_Module_Processor_InstantaneousSimpleFilterInnersBase
{
    public const MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS = 'instantaneousfilterinner-contentsections';
    public const MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS = 'instantaneousfilterinner-postsections';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS],
            [self::class, self::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS => [
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS],
            ],
            self::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS => [
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Blog:InstantaneousFilterInners:inputmodules',
            $inputmodules[$module[1]],
            $module
        )) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }

    // public function getFilter(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS:
    //             return POP_FILTER_INSTANTANEOUSCONTENTSECTIONS;

    //         case self::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS:
    //             return POP_FILTER_INSTANTANEOUSPOSTSECTIONS;
    //     }

    //     return parent::getFilter($module);
    // }

    public function getTriggerInternaltarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS:
            case self::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS:
                // Trigger when clicking on the labels inside the btn-group
                return '.pop-filterinner-instantaneous > label > input';
        }

        return parent::getTriggerInternaltarget($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS:
            case self::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS:
                $btngroups = array(
                    self::MODULE_INSTANTANEOUSFILTERINNER_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
                    self::MODULE_INSTANTANEOUSFILTERINNER_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
                );
                $btngroup = $btngroups[$module[1]];

                // Add class so we can find the element to be clicked to submit the form
                $this->appendProp($btngroup, $props, 'class', 'pop-filterinner-instantaneous');

                // Add justified style to the btn-group
                $this->appendProp($btngroup, $props, 'class', 'btn-group-justified');

                // Make it also small
                $this->setProp($btngroup, $props, 'btn-class', 'btn btn-default btn-sm');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



