<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

abstract class PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadFormComponentsBase
{
    public function getAvatarSize(array $component, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getTriggerRelationalTypeResolver(array $component): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }

    public function getTriggerSubmodule(array $component): ?array
    {
        return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_CARD];
    }

    public function initModelProps(array $component, array &$props): void
    {
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            // Pass same information to its trigger
            $trigger_layout = $this->getTriggerSubmodule($component);
            $avatar_size = $this->getAvatarSize($component, $props);
            $this->setProp($trigger_layout, $props, 'avatar-size', $avatar_size);
        }
        parent::initModelProps($component, $props);
    }
}
