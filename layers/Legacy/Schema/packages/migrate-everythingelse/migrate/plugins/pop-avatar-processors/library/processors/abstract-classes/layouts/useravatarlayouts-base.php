<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserAvatarLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_USERAVATAR];
    }

    public function getUrlField(array $module)
    {
        return 'url';
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $avatar_size = $this->getAvatarSize($module);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret = array('displayName', $avatar_field);
        $ret[] = $this->getUrlField($module);
        
        return $ret;
    }

    public function getAvatarSize(array $module)
    {
        // Default value
        return GD_AVATAR_SIZE_60;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $avatar_size = $this->getAvatarSize($module);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret['url-field'] = $this->getUrlField($module);
        $ret['avatar'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($module, $props, 'succeeding-typeResolver'),
                $avatar_field
            ),
        );

        return $ret;
    }
}
