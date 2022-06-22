<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

abstract class PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadFormComponentsBase
{
    public function getAvatarSize(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getTriggerRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_CARD];
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            // Pass same information to its trigger
            $trigger_layout = $this->getTriggerSubcomponent($component);
            $avatar_size = $this->getAvatarSize($component, $props);
            $this->setProp($trigger_layout, $props, 'avatar-size', $avatar_size);
        }
        parent::initModelProps($component, $props);
    }
}
