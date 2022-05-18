<?php

abstract class PoP_Module_Processor_LoggedInUserAvatarsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LOGGEDINUSERAVATAR];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'loadLoggedInUserAvatar');

        return $ret;
    }
}
