<?php

abstract class PoP_Module_Processor_SocialLoginElementsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor::class, PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_SOCIALLOGIN_NETWORKLINKS];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'socialLoginNetworkLink', 'links');
        return $ret;
    }

    public function getBtnClass(array $module, array &$props)
    {
        return 'btn btn-default';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['networklinks'] = getSocialloginNetworklinks();
        $ret[GD_JS_CLASSES]['link'] = $this->getBtnClass($module, $props);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'sociallogin-networklinks');

        parent::initModelProps($module, $props);
    }
}
