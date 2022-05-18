<?php

class PoP_Module_Processor_LoginGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_GROUP_LOGIN = 'group-login';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_LOGIN],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_GROUP_LOGIN:
                $ret[] = [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGIN];
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
            case self::MODULE_GROUP_LOGIN:
                $this->appendProp($componentVariation, $props, 'class', 'blockgroup-login');

                // Make the Login Block and others show the submenu
                foreach ($this->getSubComponentVariations($componentVariation) as $submodule) {
                    $this->setProp([$submodule], $props, 'show-submenu', true);

                    // Allow to set $props for the extra blocks. Eg: WSL setting the loginBlock for setting the disabled layer
                    $hooks = \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_LoginGroups:props:hooks',
                        array()
                    );
                    foreach ($hooks as $hook) {
                        $hook->setModelProps($componentVariation, $props, $this);
                    }
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


