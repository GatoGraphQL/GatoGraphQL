<?php
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_UpdateUserDataloadsBase extends PoP_Module_Processor_CreateUpdateUserDataloadsBase
{
    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        $vars = ApplicationState::getVars();
        return $vars['global-userstate']['current-user-id'];
    }

    public function getRelationalTypeResolverClass(array $module): ?string
    {
        return UserObjectTypeResolver::class;
    }
}
