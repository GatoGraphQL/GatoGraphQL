<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_USER];
    }

    public function getAvatarSize(array $component, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $data_fields = array('id', 'displayName');

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($component, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);
            $data_fields[] = $avatar_field;
        }

        if ($this->headerShowUrl($component, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($component, $props)) {
            $ret['header-show-url'] = true;
        }

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($component, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);
            $ret['avatar'] = array(
                'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                    $this->getProp($component, $props, 'succeeding-typeResolver'),
                    $avatar_field
                ),
                'size' => $avatar_size
            );
        }
        
        return $ret;
    }
}
