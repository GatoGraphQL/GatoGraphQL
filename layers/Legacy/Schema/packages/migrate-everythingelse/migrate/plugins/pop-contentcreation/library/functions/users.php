<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;

function gdCurrentUserCanEdit($post_id = null)
{
    $nameResolver = NameResolverFacade::getInstance();
    $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
    $vars = ApplicationState::getVars();
    $userID = $vars['global-userstate']['current-user-id'];
    $authors = gdGetPostauthors($post_id);
    $editCustomPostsCapability = $nameResolver->getName(LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY);
    return $userRoleTypeDataResolver->userCan(
        $userID,
        $editCustomPostsCapability
    ) && in_array(
        $userID,
        $authors
    );
}
