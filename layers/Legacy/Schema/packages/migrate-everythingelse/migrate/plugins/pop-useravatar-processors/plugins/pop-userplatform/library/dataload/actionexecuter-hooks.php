<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserAvatarProcessors_UserPlatform_ActionExecuter_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'gd_createupdate_user:additionalsCreate',
            array($this, 'additionalsCreate'),
            10,
            2
        );
    }

    public function additionalsCreate($user_id, $form_data)
    {
        // Save the user avatar
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GD_UserAvatar_Update */
        $gd_useravatar_update = $instanceManager->getInstance(GD_UserAvatar_Update::class);
        $gd_useravatar_update->savePicture($user_id, true);
    }
}

/**
 * Initialization
 */
new PoP_UserAvatarProcessors_UserPlatform_ActionExecuter_Hooks();
