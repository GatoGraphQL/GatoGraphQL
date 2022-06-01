<?php

use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\AbstractCheckpoint;
use PoP\Root\Feedback\FeedbackItemResolution;

class PoP_UserCommunities_Dataload_UserCheckpoint extends AbstractCheckpoint
{
    public final const CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY = 'checkpoint-loggedinuser-iscommunity';
    public final const CHECKPOINT_EDITINGCOMMUNITYMEMBER = 'checkpoint-editingcommunitymember';
    public final const CHECKPOINT_EDITMEMBERSHIPNONCE = 'checkpoint-editmembershipnonce';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
            [self::class, self::CHECKPOINT_EDITINGCOMMUNITYMEMBER],
            [self::class, self::CHECKPOINT_EDITMEMBERSHIPNONCE],
        );
    }

    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        $current_user_id = \PoP\Root\App::getState('current-user-id');
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY:
                if (!gdUreIsCommunity($current_user_id)) {
                    return new FeedbackItemResolution('profilenotcommunity', 'profilenotcommunity');
                }
                break;

            case self::CHECKPOINT_EDITINGCOMMUNITYMEMBER:
                // Validate the user being edited is member of the community
                $user_id = App::query(\PoPCMSSchema\Users\Constants\InputNames::USER_ID);
                $community = $current_user_id;
                $status = \PoPCMSSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
                $community_status = gdUreFindCommunityMetavalues($community, $status);

                if (empty($community_status)) {
                    return new FeedbackItemResolution('editingnotcommunitymember', 'editingnotcommunitymember');
                }
                break;

            case self::CHECKPOINT_EDITMEMBERSHIPNONCE:
                $user_id = App::query(\PoPCMSSchema\Users\Constants\InputNames::USER_ID);
                $nonce = App::query(POP_INPUTNAME_NONCE);
                if (!gdVerifyNonce($nonce, GD_NONCE_EDITMEMBERSHIPURL, $user_id)) {
                    return new FeedbackItemResolution('nonceinvalid', 'nonceinvalid');
                }
                break;
        }

        return parent::validateCheckpoint();
    }
}

