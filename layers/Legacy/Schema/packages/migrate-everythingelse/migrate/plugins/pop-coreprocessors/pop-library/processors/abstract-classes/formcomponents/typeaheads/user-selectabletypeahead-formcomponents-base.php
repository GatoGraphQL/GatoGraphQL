<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

abstract class PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase extends PoP_Module_Processor_SelectableTypeaheadFormComponentsBase
{
    public function getAvatarSize(array $module, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getTriggerRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::MODULE_LAYOUTUSER_CARD];
    }

    public function initModelProps(array $module, array &$props): void
    {
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            // Pass same information to its trigger
            $trigger_layout = $this->getTriggerSubmodule($module);
            $avatar_size = $this->getAvatarSize($module, $props);
            $this->setProp($trigger_layout, $props, 'avatar-size', $avatar_size);
        }
        parent::initModelProps($module, $props);
    }
}
