<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP = 'ure-aal-multicomponentactionwrapper-layoutuser-membership';
    public final const COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY = 'ure-aal-quicklinkgroupactionwrapper-user-joinedcommunity';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP,
            self::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:
                $ret[] = [Wassup_URE_AAL_Module_Processor_MultiMembership::class, Wassup_URE_AAL_Module_Processor_MultiMembership::COMPONENT_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP];
                break;

            case self::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:
                $ret[] = [GD_URE_AAL_Module_Processor_QuicklinkGroups::class, GD_URE_AAL_Module_Processor_QuicklinkGroups::COMPONENT_UREAAL_QUICKLINKGROUP_USER_JOINEDCOMMUNITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP:
                return /* @todo Re-do this code! Left undone */ new Field('isAction', ['action' => URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP]);

            case self::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY:
                return /* @todo Re-do this code! Left undone */ new Field('isAction', ['action' => URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY]);
        }

        return null;
    }
}



