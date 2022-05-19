<?php

abstract class GD_WSL_Module_Processor_NetworkLinkBlocksBase extends PoP_Module_Processor_BlocksBase
{
    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'addDomainClass');

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Visible only if the user not logged in
        $this->appendProp($component, $props, 'class', 'visible-notloggedin');

        // Add the LoginBlock target, as to add the Disabled Layer on top
        // while waiting for the server authenticating the FB/Twitter user
        // Allow to override this value. By default, it is this same block, but can be
        // the containing GROUP_LOGIN
        $blocktarget = '#'./*$props['block-id']*/$this->getFrontendId($component, $props);
        $this->setProp($component, $props, 'loginblock-target', $blocktarget);
        $this->mergeProp(
            $component,
            $props,
            'params',
            array(
                'data-loginblock' => $this->getProp($component, $props, 'loginblock-target')
            )
        );
        parent::initModelProps($component, $props);
    }
}
