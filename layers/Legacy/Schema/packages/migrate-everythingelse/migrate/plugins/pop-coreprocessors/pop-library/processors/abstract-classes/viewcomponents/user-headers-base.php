<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserViewComponentHeadersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_USER];
    }

    public function getAvatarSize(array $module, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $data_fields = array('id', 'displayName');

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($module, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);
            $data_fields[] = $avatar_field;
        }

        if ($this->headerShowUrl($module, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($module, $props)) {
            $ret['header-show-url'] = true;
        }

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($module, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);
            $ret['avatar'] = array(
                'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                    $this->getProp($module, $props, 'succeeding-typeResolver'),
                    $avatar_field
                ),
                'size' => $avatar_size
            );
        }
        
        return $ret;
    }
}
