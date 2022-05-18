<?php

class PoP_UserCommunities_ComponentProcessor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const MODULE_BUTTONGROUP_MYUSERS = 'ure-buttongroup-myusers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONGROUP_MYUSERS],
        );
    }

    protected function getHeadersdataScreen(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONGROUP_MYUSERS:
                return POP_URE_SCREEN_MYUSERS;
        }

        return parent::getHeadersdataScreen($componentVariation, $props);
    }

    protected function getHeadersdataFormats(array $componentVariation, array &$props)
    {

        // We can initially have a common format scheme depending on the screen
        $screen = $this->getHeadersdataScreen($componentVariation, $props);
        switch ($screen) {
            case POP_URE_SCREEN_MYUSERS:
                return array(
                    POP_FORMAT_TABLE => array(),
                    POP_FORMAT_FULLVIEW => array(),
                );
        }

        return parent::getHeadersdataFormats($componentVariation, $props);
    }
}

