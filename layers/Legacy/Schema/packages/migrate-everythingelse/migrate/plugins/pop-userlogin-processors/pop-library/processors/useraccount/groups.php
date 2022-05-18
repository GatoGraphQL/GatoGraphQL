<?php

class PoP_Module_Processor_UserAccountGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_GROUP_LOGGEDINUSERDATA = 'group-loggedinuserdata';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GROUP_LOGGEDINUSERDATA],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_GROUP_LOGGEDINUSERDATA => POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_GROUP_LOGGEDINUSERDATA:
                $ret = array_merge(
                    $ret,
                    PoP_Module_Processor_UserAccountUtils::getLoginComponents()
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_GROUP_LOGGEDINUSERDATA:
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


