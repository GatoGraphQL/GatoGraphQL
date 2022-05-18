<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP = 'ure-aal-multicomponentactionwrapper-layoutuser-membership';
    public final const COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY = 'ure-aal-quicklinkgroupactionwrapper-user-joinedcommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP],
            [self::class, self::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:
                $ret[] = [Wassup_URE_AAL_Module_Processor_MultiMembership::class, Wassup_URE_AAL_Module_Processor_MultiMembership::COMPONENT_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP];
                break;

            case self::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkGroups::class, GD_URE_AAL_Module_Processor_QuicklinkGroups::COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:
                return FieldQueryInterpreterFacade::getInstance()->getField('isAction', ['action' => URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP]);

            case self::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:
                return FieldQueryInterpreterFacade::getInstance()->getField('isAction', ['action' => URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY]);
        }

        return null;
    }
}



