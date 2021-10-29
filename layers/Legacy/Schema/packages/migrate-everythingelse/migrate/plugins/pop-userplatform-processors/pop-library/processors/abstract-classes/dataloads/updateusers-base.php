<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

abstract class PoP_Module_Processor_UpdateUserDataloadsBase extends PoP_Module_Processor_CreateUpdateUserDataloadsBase
{
    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        $vars = ApplicationState::getVars();
        return $vars['global-userstate']['current-user-id'];
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->getInstanceManager()->getInstance(UserObjectTypeResolver::class);
    }
}
