<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_UpdateUserDataloadsBase extends PoP_Module_Processor_CreateUpdateUserDataloadsBase
{
    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        $vars = ApplicationState::getVars();
        return $vars['global-userstate']['current-user-id'];
    }

    public function getTypeResolverClass(array $module): ?string
    {
        return UserTypeResolver::class;
    }
}
