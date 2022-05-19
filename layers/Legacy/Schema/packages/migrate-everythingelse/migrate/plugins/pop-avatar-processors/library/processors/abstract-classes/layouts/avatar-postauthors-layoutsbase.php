<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostAuthorAvatarLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_AUTHORAVATAR];
    }

    public function getUrlField(array $component, array &$props)
    {
        return 'url';
    }

    public function getAvatarSize(array $component, array &$props)
    {
        return GD_AVATAR_SIZE_60;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);
    
        $url_field = $this->getUrlField($component, $props);
        $avatar_size = $this->getAvatarSize($component, $props);
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

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $avatar_size = $this->getAvatarSize($component, $props);
        $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

        $ret['avatar'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $avatar_field
            ),
            'size' => $avatar_size
        );
        $ret['url-field'] = $this->getUrlField($component, $props);
        
        return $ret;
    }
}
