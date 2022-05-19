<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserAvatarLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_USERAVATAR];
    }

    public function getUrlField(array $component)
    {
        return 'url';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $avatar_size = $this->getAvatarSize($component);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret = array('displayName', $avatar_field);
        $ret[] = $this->getUrlField($component);
        
        return $ret;
    }

    public function getAvatarSize(array $component)
    {
        // Default value
        return GD_AVATAR_SIZE_60;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $avatar_size = $this->getAvatarSize($component);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret['url-field'] = $this->getUrlField($component);
        $ret['avatar'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $avatar_field
            ),
        );

        return $ret;
    }
}
