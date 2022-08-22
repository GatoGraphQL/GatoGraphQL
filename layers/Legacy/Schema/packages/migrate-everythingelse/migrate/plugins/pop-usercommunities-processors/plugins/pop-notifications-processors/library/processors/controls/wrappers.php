<?php
class GD_URE_AAL_Module_Processor_QuicklinkButtonGroupWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP = 'ure-aal-quicklinkbuttongroupwrapper-editusermembership';
    public final const COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS = 'ure-aal-quicklinkbuttongroupwrapper-viewallmembers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP,
            self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP];
                break;

            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:
                return /* @todo Re-do this code! Left undone */ new Field(
                    'equals',
                    [
                        'value1' => 'objectID()', /* @todo @todo Re-do this code! Use ResolvedVariableReference */
                        'value2' => 'me()', /* @todo @todo Re-do this code! Use ResolvedVariableReference */
                    ],
                    'object-id-equals-logged-in-user-id'
                );
        }

        return null;
    }
}



