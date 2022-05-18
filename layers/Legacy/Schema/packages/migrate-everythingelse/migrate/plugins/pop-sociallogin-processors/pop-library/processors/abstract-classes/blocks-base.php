<?php

abstract class GD_WSL_Module_Processor_NetworkLinkBlocksBase extends PoP_Module_Processor_BlocksBase
{
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'addDomainClass');

        return $ret;
    }
    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Visible only if the user not logged in
        $this->appendProp($componentVariation, $props, 'class', 'visible-notloggedin');

        // Add the LoginBlock target, as to add the Disabled Layer on top
        // while waiting for the server authenticating the FB/Twitter user
        // Allow to override this value. By default, it is this same block, but can be
        // the containing GROUP_LOGIN
        $blocktarget = '#'./*$props['block-id']*/$this->getFrontendId($componentVariation, $props);
        $this->setProp($componentVariation, $props, 'loginblock-target', $blocktarget);
        $this->mergeProp(
            $componentVariation,
            $props,
            'params',
            array(
                'data-loginblock' => $this->getProp($componentVariation, $props, 'loginblock-target')
            )
        );
        parent::initModelProps($componentVariation, $props);
    }
}
