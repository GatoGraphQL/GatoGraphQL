<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserAvatarLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_USERAVATAR];
    }

    public function getUrlField(array $componentVariation)
    {
        return 'url';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $avatar_size = $this->getAvatarSize($componentVariation);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret = array('displayName', $avatar_field);
        $ret[] = $this->getUrlField($componentVariation);
        
        return $ret;
    }

    public function getAvatarSize(array $componentVariation)
    {
        // Default value
        return GD_AVATAR_SIZE_60;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $avatar_size = $this->getAvatarSize($componentVariation);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret['url-field'] = $this->getUrlField($componentVariation);
        $ret['avatar'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
                $avatar_field
            ),
        );

        return $ret;
    }
}
