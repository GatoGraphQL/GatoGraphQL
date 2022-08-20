<?php
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;

class GD_DataLoad_QueryInputOutputHandler_EditPost extends ActionExecutionQueryInputOutputHandler
{
    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, string|int|array $objectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        $ret[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = \PoP\Root\App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID);

        // If the user is sending the '_wpnonce', because has sent a POST editing a post, then use that one, and make the nonce validation with it
        // The nonce must be passed already in the link, otherwise it will not work
        $ret[POP_INPUTNAME_NONCE] = \PoP\Root\App::query(POP_INPUTNAME_NONCE, '');

        return $ret;
    }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_EditPost();
