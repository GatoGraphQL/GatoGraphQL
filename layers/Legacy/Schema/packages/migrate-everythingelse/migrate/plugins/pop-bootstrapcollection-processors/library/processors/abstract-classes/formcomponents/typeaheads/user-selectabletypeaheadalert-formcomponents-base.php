<?php
use PoP\ConfigurationComponentModel\Facades\TypeResolverHelperService\TypeResolverHelperServiceFacade;

abstract class PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase
{
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getProp($component, $props, 'avatar-size');
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

    public function getSelectedComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_CARD];
    }
}
