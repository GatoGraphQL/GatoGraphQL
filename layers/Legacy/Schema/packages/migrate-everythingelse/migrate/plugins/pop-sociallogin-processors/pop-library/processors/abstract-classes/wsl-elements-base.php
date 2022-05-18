<?php

abstract class PoP_Module_Processor_SocialLoginElementsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor::class, PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_SOCIALLOGIN_NETWORKLINKS];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'socialLoginNetworkLink', 'links');
        return $ret;
    }

    public function getBtnClass(array $component, array &$props)
    {
        return 'btn btn-default';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['networklinks'] = getSocialloginNetworklinks();
        $ret[GD_JS_CLASSES]['link'] = $this->getBtnClass($component, $props);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'sociallogin-networklinks');

        parent::initModelProps($component, $props);
    }
}
