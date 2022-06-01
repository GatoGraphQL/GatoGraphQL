<?php
use PoP\ComponentModel\Checkpoints\AbstractCheckpoint;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\App;

class GD_ContentCreation_Dataload_UserCheckpoint extends AbstractCheckpoint
{
    public final const CHECKPOINT_USERCANEDIT = 'checkpoint-usercanedit';
    public final const CHECKPOINT_EDITPOSTNONCE = 'checkpoint-editpostnonce';

    /**
     * @todo Migrate to not using $checkpoint
     */
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_USERCANEDIT:
                // Check if the user can edit the specific post
                $post_id = App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID);
                if (!gdCurrentUserCanEdit($post_id)) {
                    return new FeedbackItemResolution('usercannotedit', 'usercannotedit');
                }
                break;

            case self::CHECKPOINT_EDITPOSTNONCE:
                $post_id = App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID);
                $nonce = App::query(POP_INPUTNAME_NONCE);
                if (!gdVerifyNonce($nonce, GD_NONCE_EDITURL, $post_id)) {
                    return new FeedbackItemResolution('nonceinvalid', 'nonceinvalid');
                }
                break;
        }

        return parent::validateCheckpoint();
    }
}

