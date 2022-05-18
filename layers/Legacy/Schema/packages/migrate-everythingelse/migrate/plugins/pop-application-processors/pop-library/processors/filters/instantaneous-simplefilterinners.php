<?php

class PoP_Module_Processor_InstantaneousSimpleFilterInners extends PoP_Module_Processor_InstantaneousSimpleFilterInnersBase
{
    public final const MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS = 'instantaneousfilterinputcontainer-contentsections';
    public final const MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS = 'instantaneousfilterinputcontainer-postsections';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS],
            [self::class, self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

        $inputmodules = [
            self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS => [
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS],
            ],
            self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS => [
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
            ],
        ];
        if ($modules = \PoP\Root\App::applyFilters(
            'Blog:InstantaneousFilterInners:inputmodules',
            $inputmodules[$componentVariation[1]],
            $componentVariation
        )) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }

    // public function getFilter(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS:
    //             return POP_FILTER_INSTANTANEOUSCONTENTSECTIONS;

    //         case self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS:
    //             return POP_FILTER_INSTANTANEOUSPOSTSECTIONS;
    //     }

    //     return parent::getFilter($componentVariation);
    // }

    public function getTriggerInternaltarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS:
            case self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS:
                // Trigger when clicking on the labels inside the btn-group
                return '.pop-filterinputcontainer-instantaneous > label > input';
        }

        return parent::getTriggerInternaltarget($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS:
            case self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS:
                $btngroups = array(
                    self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
                    self::MODULE_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
                );
                $btngroup = $btngroups[$componentVariation[1]];

                // Add class so we can find the element to be clicked to submit the form
                $this->appendProp($btngroup, $props, 'class', 'pop-filterinputcontainer-instantaneous');

                // Add justified style to the btn-group
                $this->appendProp($btngroup, $props, 'class', 'btn-group-justified');

                // Make it also small
                $this->setProp($btngroup, $props, 'btn-class', 'btn btn-default btn-sm');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



