<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class GD_URE_AAL_Module_Processor_QuicklinkButtonGroupWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP = 'ure-aal-quicklinkbuttongroupwrapper-editusermembership';
    public final const COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS = 'ure-aal-quicklinkbuttongroupwrapper-viewallmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP],
            [self::class, self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS],
        );
    }

    public function getConditionSucceededSubcomponents(array $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP];
                break;

            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::class, GD_URE_AAL_Module_Processor_QuicklinkButtonGroups::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_EDITUSERMEMBERSHIP:
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUPWRAPPER_VIEWALLMEMBERS:
                $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                return $fieldQueryInterpreter->getField(
                    'equals',
                    [
                        'value1' => $fieldQueryInterpreter->createFieldArgValueAsFieldFromFieldName('objectID'),
                        'value2' => $fieldQueryInterpreter->createFieldArgValueAsFieldFromFieldName('me'),
                    ],
                    'object-id-equals-logged-in-user-id'
                );
        }

        return null;
    }
}



