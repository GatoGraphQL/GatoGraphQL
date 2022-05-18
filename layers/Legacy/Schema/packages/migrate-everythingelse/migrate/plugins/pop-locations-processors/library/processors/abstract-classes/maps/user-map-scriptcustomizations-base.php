<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserMapScriptCustomizationsBase extends PoP_Module_Processor_MapScriptCustomizationsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPTCUSTOMIZATION_USER];
    }

    public function getAvatarMarker(array $component)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getAvatarInfowindow(array $component)
    {
        return GD_AVATAR_SIZE_64;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
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

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size_sm = $this->getAvatarMarker($component);
            $avatar_field_sm = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size_sm);
            $avatar_size_md = $this->getAvatarInfowindow($component);
            $avatar_field_md = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size_md);

            $ret['avatars'] = array(
                'sm' => array(
                    'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                        $this->getProp($component, $props, 'succeeding-typeResolver'),
                        $avatar_field_sm
                    ),
                    'size' => $avatar_size_sm
                ),
                'md' => array(
                    'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                        $this->getProp($component, $props, 'succeeding-typeResolver'),
                        $avatar_field_md
                    ),
                    'size' => $avatar_size_md
                )
            );
        }
        
        return $ret;
    }
}
