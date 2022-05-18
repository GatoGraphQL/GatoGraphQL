<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostAuthorAvatarLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_AUTHORAVATAR];
    }

    public function getUrlField(array $componentVariation, array &$props)
    {
        return 'url';
    }

    public function getAvatarSize(array $componentVariation, array &$props)
    {
        return GD_AVATAR_SIZE_60;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
    
        $url_field = $this->getUrlField($componentVariation, $props);
        $avatar_size = $this->getAvatarSize($componentVariation, $props);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        return array_merge(
            $ret,
            array(
                $avatar_field,
                $url_field,
                'displayName'
            )
        );
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $avatar_size = $this->getAvatarSize($componentVariation, $props);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret['avatar'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
                $avatar_field
            ),
            'size' => $avatar_size
        );
        $ret['url-field'] = $this->getUrlField($componentVariation, $props);
        
        return $ret;
    }
}
