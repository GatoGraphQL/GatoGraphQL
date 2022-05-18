<?php

abstract class PoP_Module_Processor_LoggedInUserAvatarsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LOGGEDINUSERAVATAR];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'loadLoggedInUserAvatar');

        return $ret;
    }
}
