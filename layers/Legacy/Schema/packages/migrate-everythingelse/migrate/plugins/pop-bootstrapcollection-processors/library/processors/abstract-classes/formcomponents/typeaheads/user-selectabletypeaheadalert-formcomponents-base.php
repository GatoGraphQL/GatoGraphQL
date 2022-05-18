<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase
{
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getProp($component, $props, 'avatar-size');
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

    public function getSelectedModule(array $component)
    {
        return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_CARD];
    }
}
