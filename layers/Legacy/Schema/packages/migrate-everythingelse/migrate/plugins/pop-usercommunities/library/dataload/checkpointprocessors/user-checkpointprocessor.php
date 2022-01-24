<?php

use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\State\ApplicationState;

class PoP_UserCommunities_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY = 'checkpoint-loggedinuser-iscommunity';
    public const CHECKPOINT_EDITINGCOMMUNITYMEMBER = 'checkpoint-editingcommunitymember';
    public const CHECKPOINT_EDITMEMBERSHIPNONCE = 'checkpoint-editmembershipnonce';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
            [self::class, self::CHECKPOINT_EDITINGCOMMUNITYMEMBER],
            [self::class, self::CHECKPOINT_EDITMEMBERSHIPNONCE],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?Error
    {
        $current_user_id = \PoP\Root\App::getState('current-user-id');
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY:
                if (!gdUreIsCommunity($current_user_id)) {
                    return new Error('profilenotcommunity');
                }
                break;

            case self::CHECKPOINT_EDITINGCOMMUNITYMEMBER:
                // Validate the user being edited is member of the community
                $user_id = $_GET[\PoPCMSSchema\Users\Constants\InputNames::USER_ID];
                $community = $current_user_id;
                $status = \PoPCMSSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
                $community_status = gdUreFindCommunityMetavalues($community, $status);

                if (empty($community_status)) {
                    return new Error('editingnotcommunitymember');
                }
                break;

            case self::CHECKPOINT_EDITMEMBERSHIPNONCE:
                $user_id = $_GET[\PoPCMSSchema\Users\Constants\InputNames::USER_ID];
                $nonce = $_GET[POP_INPUTNAME_NONCE];
                if (!gdVerifyNonce($nonce, GD_NONCE_EDITMEMBERSHIPURL, $user_id)) {
                    return new Error('nonceinvalid');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

