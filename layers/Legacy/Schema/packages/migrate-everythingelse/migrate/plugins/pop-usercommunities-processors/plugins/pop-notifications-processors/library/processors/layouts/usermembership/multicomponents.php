<?php

class Wassup_URE_AAL_Module_Processor_MultiMembership extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP = 'ure-aal-multicomponent-layoutuser-membership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberStatusLayouts::class, Wassup_URE_AAL_Module_Processor_MemberStatusLayouts::MODULE_UREAAL_LAYOUTUSER_MEMBERSTATUS];
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts::class, Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts::MODULE_UREAAL_LAYOUTUSER_MEMBERPRIVILEGES];
                $ret[] = [Wassup_URE_AAL_Module_Processor_MemberTagsLayouts::class, Wassup_URE_AAL_Module_Processor_MemberTagsLayouts::MODULE_UREAAL_LAYOUTUSER_MEMBERTAGS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_UREAAL_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:
                $this->appendProp($module, $props, 'class', 'pop-usermembership');
                foreach ($this->getSubmodules($module) as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'item');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



