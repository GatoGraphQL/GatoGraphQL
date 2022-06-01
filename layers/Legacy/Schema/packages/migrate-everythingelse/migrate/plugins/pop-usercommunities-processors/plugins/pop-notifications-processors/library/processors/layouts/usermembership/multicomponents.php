<?php

class Wassup_URE_AAL_Module_Processor_MultiMembership extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP = 'ure-aal-multicomponent-layoutuser-membership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberStatusLayouts::class, Wassup_URE_AAL_Module_Processor_MemberStatusLayouts::COMPONENT_UREAAL_LAYOUTUSER_MEMBERSTATUS];
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts::class, Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts::COMPONENT_UREAAL_LAYOUTUSER_MEMBERPRIVILEGES];
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberTagsLayouts::class, Wassup_URE_AAL_Module_Processor_MemberTagsLayouts::COMPONENT_UREAAL_LAYOUTUSER_MEMBERTAGS];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:
                $this->appendProp($component, $props, 'class', 'pop-usermembership');
                foreach ($this->getSubcomponents($component) as $subcomponent) {
                    $this->appendProp([$subcomponent], $props, 'class', 'item');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



