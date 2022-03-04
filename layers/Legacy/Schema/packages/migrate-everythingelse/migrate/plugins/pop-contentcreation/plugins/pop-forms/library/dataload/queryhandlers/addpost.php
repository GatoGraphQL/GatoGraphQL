<?php
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Root\App;

class GD_DataLoad_QueryInputOutputHandler_AddPost extends ActionExecutionQueryInputOutputHandler
{
    public function getQueryParams($data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        // Empty params needed for initialBlockMemory:
        // We must send these params empty at the beginning. That way, when clicking on "Reset", it will override
        // the block current param values (eg: after creating a post) with these empty ones
        $ret[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = '';
        $ret[POP_INPUTNAME_NONCE] = '';

        // If the AddPost queryhandler is being used as EditPost queryhandler (eg: first AddPost, from there create a post, since that moment on it will actually be an EditPost)
        // then the 'pid' is already sent in the request. Then already use it.
        // Otherwise, if we create a post successfully, then when editing the validation fails, it will delete the
        // 'pid' from the response and treat the next iteration as yet a new post
        $pid = App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID);
        if ($pid !== null) {
            $ret[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = $pid;
            $ret[POP_INPUTNAME_NONCE] = gdCreateNonce(GD_NONCE_EDITURL, $pid);
        }
        if ($executed) {
            // Check if there are errors or if it was successful, and add corresponding messages.
            // $data = $this->getFormExecutedResponse($executed);
            if ($executed[ResponseConstants::SUCCESS]) {
                $objectIDs = $dbObjectIDOrIDs;
                $pid = $objectIDs[0];
                $nonce = $pid ? gdCreateNonce(GD_NONCE_EDITURL, $pid) : '';
                $ret[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = $pid;
                $ret[POP_INPUTNAME_NONCE] = $nonce;
            }
        }

        return $ret;
    }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_AddPost();
