<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPCMSSchema\CustomPostMutations\LooseContracts\LooseContractSet;
use PoPCMSSchema\UserRoles\Facades\UserRoleTypeAPIFacade;

function gdCurrentUserCanEdit($post_id = null)
{
    $nameResolver = NameResolverFacade::getInstance();
    $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
    $userID = \PoP\Root\App::getState('current-user-id');
    $authors = gdGetPostauthors($post_id);
    $editCustomPostsCapability = $nameResolver->getName(LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY);
    return $userRoleTypeAPI->userCan(
        $userID,
        $editCustomPostsCapability
    ) && in_array(
        $userID,
        $authors
    );
}
