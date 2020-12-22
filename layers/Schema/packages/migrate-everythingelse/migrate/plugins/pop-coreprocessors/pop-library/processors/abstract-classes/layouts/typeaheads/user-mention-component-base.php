<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserMentionComponentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_MENTION_COMPONENT];
    }
    
    public function getDataFields(array $module, array &$props): array
    {
        // Can't use "user-nicename", because @Mentions plugin does not store the "-" in the html attribute, so it would
        // save the entry as data-usernicename. To avoid conflicts, just remove the "-"
        $data_fields = array('displayName', 'nicename', 'mentionQueryby');
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            // Important: for Component the size is fixed! It can't be changed from 'avatar-40', because it is hardcoded
            // in layoutuser-mention-component.tmpl
            $avatar_size = GD_AVATAR_SIZE_40;
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);
            $data_fields[] = $avatar_field;
        }

        return $data_fields;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = GD_AVATAR_SIZE_40;
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

            $ret['avatar'] = array(
                'name' => FieldQueryInterpreterFacade::getInstance()->getFieldOutputKey($avatar_field),
                'size' => $avatar_size
            );
        }
        
        return $ret;
    }
}
