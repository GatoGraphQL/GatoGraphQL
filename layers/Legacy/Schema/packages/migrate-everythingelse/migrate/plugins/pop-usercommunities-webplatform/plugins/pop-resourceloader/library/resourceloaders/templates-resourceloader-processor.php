<?php

class URE_PoPProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUTUSER_MEMBERPRIVILEGES = 'layoutuser_memberprivileges';
    public final const RESOURCE_LAYOUTUSER_MEMBERSTATUS = 'layoutuser_memberstatus';
    public final const RESOURCE_LAYOUTUSER_MEMBERTAGS = 'layoutuser_membertags';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUTUSER_MEMBERPRIVILEGES],
            [self::class, self::RESOURCE_LAYOUTUSER_MEMBERSTATUS],
            [self::class, self::RESOURCE_LAYOUTUSER_MEMBERTAGS],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUTUSER_MEMBERPRIVILEGES => POP_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES,
            self::RESOURCE_LAYOUTUSER_MEMBERSTATUS => POP_TEMPLATE_LAYOUTUSER_MEMBERSTATUS,
            self::RESOURCE_LAYOUTUSER_MEMBERTAGS => POP_TEMPLATE_LAYOUTUSER_MEMBERTAGS,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERCOMMUNITIESWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_USERCOMMUNITIESWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_USERCOMMUNITIESWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_LAYOUTUSER_MEMBERPRIVILEGES:
            case self::RESOURCE_LAYOUTUSER_MEMBERSTATUS:
            case self::RESOURCE_LAYOUTUSER_MEMBERTAGS:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_LABELS];
                break;
        }

        return $dependencies;
    }
}


