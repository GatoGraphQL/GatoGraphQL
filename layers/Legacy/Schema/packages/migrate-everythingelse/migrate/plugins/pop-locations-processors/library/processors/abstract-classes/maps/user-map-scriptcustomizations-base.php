<?php
use PoP\ConfigurationComponentModel\Facades\HelperServices\TypeResolverHelperServiceFacade;

abstract class PoP_Module_Processor_UserMapScriptCustomizationsBase extends PoP_Module_Processor_MapScriptCustomizationsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPTCUSTOMIZATION_USER];
    }

    public function getAvatarMarker(\PoP\ComponentModel\Component\Component $component)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getAvatarInfowindow(\PoP\ComponentModel\Component\Component $component)
    {
        return GD_AVATAR_SIZE_64;
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $data_fields = array('id', 'displayName', 'url', 'shortDescriptionFormatted');

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size_sm = $this->getAvatarMarker($component);
            $avatar_field_sm = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size_sm);
            $avatar_size_md = $this->getAvatarInfowindow($component);
            $avatar_field_md = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size_md);
            $data_fields[] = $avatar_field_sm;
            $data_fields[] = $avatar_field_md;
        }

        return $data_fields;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size_sm = $this->getAvatarMarker($component);
            $avatar_field_sm = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size_sm);
            $avatar_size_md = $this->getAvatarInfowindow($component);
            $avatar_field_md = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size_md);

            $ret['avatars'] = array(
                'sm' => array(
                    'name' => TypeResolverHelperServiceFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                        $this->getProp($component, $props, 'succeeding-typeResolver'),
                        $avatar_field_sm // @todo Fix: pass LeafField
                    ),
                    'size' => $avatar_size_sm
                ),
                'md' => array(
                    'name' => TypeResolverHelperServiceFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                        $this->getProp($component, $props, 'succeeding-typeResolver'),
                        $avatar_field_md // @todo Fix: pass LeafField
                    ),
                    'size' => $avatar_size_md
                )
            );
        }
        
        return $ret;
    }
}
