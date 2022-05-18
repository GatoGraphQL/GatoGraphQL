<?php

abstract class PoP_Module_Processor_SocialLoginElementsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor::class, PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_SOCIALLOGIN_NETWORKLINKS];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'socialLoginNetworkLink', 'links');
        return $ret;
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-default';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['networklinks'] = getSocialloginNetworklinks();
        $ret[GD_JS_CLASSES]['link'] = $this->getBtnClass($componentVariation, $props);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'sociallogin-networklinks');

        parent::initModelProps($componentVariation, $props);
    }
}
