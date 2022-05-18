<?php

class PoP_Module_Processor_UserAccountGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_GROUP_LOGGEDINUSERDATA = 'group-loggedinuserdata';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_LOGGEDINUSERDATA],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_GROUP_LOGGEDINUSERDATA => POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_GROUP_LOGGEDINUSERDATA:
                $ret = array_merge(
                    $ret,
                    PoP_Module_Processor_UserAccountUtils::getLoginComponentVariations()
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_GROUP_LOGGEDINUSERDATA:
                $this->appendProp($componentVariation, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


