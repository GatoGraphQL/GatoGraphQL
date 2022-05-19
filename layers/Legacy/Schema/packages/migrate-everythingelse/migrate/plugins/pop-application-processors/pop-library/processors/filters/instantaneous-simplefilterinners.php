<?php

class PoP_Module_Processor_InstantaneousSimpleFilterInners extends PoP_Module_Processor_InstantaneousSimpleFilterInnersBase
{
    public final const COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS = 'instantaneousfilterinputcontainer-contentsections';
    public final const COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS = 'instantaneousfilterinputcontainer-postsections';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS],
            [self::class, self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS],
        );
    }

    protected function getInputSubcomponents(array $component)
    {
        $ret = parent::getInputSubcomponents($component);

        $inputComponents = [
            self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS => [
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS],
            ],
            self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS => [
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Blog:InstantaneousFilterInners:inputComponents',
            $inputComponents[$component[1]],
            $component
        )) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        return $ret;
    }

    // public function getFilter(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS:
    //             return POP_FILTER_INSTANTANEOUSCONTENTSECTIONS;

    //         case self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS:
    //             return POP_FILTER_INSTANTANEOUSPOSTSECTIONS;
    //     }

    //     return parent::getFilter($component);
    // }

    public function getTriggerInternaltarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS:
            case self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS:
                // Trigger when clicking on the labels inside the btn-group
                return '.pop-filterinputcontainer-instantaneous > label > input';
        }

        return parent::getTriggerInternaltarget($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS:
            case self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS:
                $btngroups = array(
                    self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
                    self::COMPONENT_INSTANTANEOUSFILTERINPUTCONTAINER_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
                );
                $btngroup = $btngroups[$component[1]];

                // Add class so we can find the element to be clicked to submit the form
                $this->appendProp($btngroup, $props, 'class', 'pop-filterinputcontainer-instantaneous');

                // Add justified style to the btn-group
                $this->appendProp($btngroup, $props, 'class', 'btn-group-justified');

                // Make it also small
                $this->setProp($btngroup, $props, 'btn-class', 'btn btn-default btn-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



