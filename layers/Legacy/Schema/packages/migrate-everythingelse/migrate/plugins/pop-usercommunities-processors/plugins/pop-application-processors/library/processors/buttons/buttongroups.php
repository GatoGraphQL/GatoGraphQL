<?php

class PoP_UserCommunities_ComponentProcessor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const COMPONENT_BUTTONGROUP_MYUSERS = 'ure-buttongroup-myusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONGROUP_MYUSERS],
        );
    }

    protected function getHeadersdataScreen(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONGROUP_MYUSERS:
                return POP_URE_SCREEN_MYUSERS;
        }

        return parent::getHeadersdataScreen($component, $props);
    }

    protected function getHeadersdataFormats(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // We can initially have a common format scheme depending on the screen
        $screen = $this->getHeadersdataScreen($component, $props);
        switch ($screen) {
            case POP_URE_SCREEN_MYUSERS:
                return array(
                    POP_FORMAT_TABLE => array(),
                    POP_FORMAT_FULLVIEW => array(),
                );
        }

        return parent::getHeadersdataFormats($component, $props);
    }
}

