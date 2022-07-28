<?php
use PoP\ConfigurationComponentModel\Facades\HelperServices\TypeResolverHelperServiceFacade;

abstract class PoP_Module_Processor_UserViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_USER];
    }

    public function getAvatarSize(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
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

    public function headerShowUrl(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
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
                'name' => TypeResolverHelperServiceFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                    $this->getProp($component, $props, 'succeeding-typeResolver'),
                    $avatar_field // @todo Fix: pass LeafField
                ),
                'size' => $avatar_size
            );
        }
        
        return $ret;
    }
}
