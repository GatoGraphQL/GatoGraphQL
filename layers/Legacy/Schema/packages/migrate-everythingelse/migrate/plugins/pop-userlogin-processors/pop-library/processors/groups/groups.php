<?php

class PoP_Module_Processor_LoginGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_GROUP_LOGIN = 'group-login';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_LOGIN],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_GROUP_LOGIN:
                $ret[] = [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGIN];
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
            case self::MODULE_GROUP_LOGIN:
                $this->appendProp($component, $props, 'class', 'blockgroup-login');

                // Make the Login Block and others show the submenu
                foreach ($this->getSubComponents($component) as $subComponent) {
                    $this->setProp([$subComponent], $props, 'show-submenu', true);

                    // Allow to set $props for the extra blocks. Eg: WSL setting the loginBlock for setting the disabled layer
                    $hooks = \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_LoginGroups:props:hooks',
                        array()
                    );
                    foreach ($hooks as $hook) {
                        $hook->setModelProps($component, $props, $this);
                    }
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


