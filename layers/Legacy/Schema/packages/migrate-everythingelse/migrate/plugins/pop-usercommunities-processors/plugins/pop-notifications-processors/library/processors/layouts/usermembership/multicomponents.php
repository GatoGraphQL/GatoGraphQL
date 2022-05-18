<?php

class Wassup_URE_AAL_Module_Processor_MultiMembership extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP = 'ure-aal-multicomponent-layoutuser-membership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberStatusLayouts::class, Wassup_URE_AAL_Module_Processor_MemberStatusLayouts::MODULE_UREAAL_LAYOUTUSER_MEMBERSTATUS];
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts::class, Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts::MODULE_UREAAL_LAYOUTUSER_MEMBERPRIVILEGES];
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberTagsLayouts::class, Wassup_URE_AAL_Module_Processor_MemberTagsLayouts::MODULE_UREAAL_LAYOUTUSER_MEMBERTAGS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:
                $this->appendProp($component, $props, 'class', 'pop-usermembership');
                foreach ($this->getSubComponents($component) as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'item');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



