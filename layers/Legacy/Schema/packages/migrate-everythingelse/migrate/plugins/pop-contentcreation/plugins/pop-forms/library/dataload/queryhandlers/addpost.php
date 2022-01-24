<?php
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;

class GD_DataLoad_QueryInputOutputHandler_AddPost extends ActionExecutionQueryInputOutputHandler
{
    // function getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     // If $executed != null, then $checkpoint succeded, no need to ask for this condition before printing the messages
    //     if ($executed) {

    //         // Check if there are errors or if it was successful, and add corresponding messages.
    //         $data = $this->getFormExecutedResponse($executed);
    //         if ($data[ResponseConstants::SUCCESS]) {

    //             // If the post was not just created but actually updated (created first and then on that same page updated it)
    //             // then change the success code
    //             $pid = $dbobjectids[0];
    //             if ($pid == $_GET[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID]) {

    //                 $ret['msgs'][0]['header']['code'] = 'update-success-header';
    //             }
    //         }
    //     }

    //     return $ret;
    // }

    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
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
        if (isset($_GET[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID])) {
            $pid = $_GET[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID];
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

    // function getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     // Empty params needed for initialBlockMemory:
    //     // We must send these params empty at the beginning. That way, when clicking on "Reset", it will override
    //     // the block current param values (eg: after creating a post) with these empty ones
    //     $ret[ParamConstants::PARAMS][\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = '';
    //     $ret[ParamConstants::PARAMS][POP_INPUTNAME_NONCE] = '';

    //     // If the AddPost queryhandler is being used as EditPost queryhandler (eg: first AddPost, from there create a post, since that moment on it will actually be an EditPost)
    //     // then the 'pid' is already sent in the request. Then already use it.
    //     // Otherwise, if we create a post successfully, then when editing the validation fails, it will delete the
    //     // 'pid' from the response and treat the next iteration as yet a new post
    //     if ($pid = $_GET[\PoPCMSSchema\Posts\Constants\InputNames::POST_ID]) {
    //         $ret[ParamConstants::PARAMS][\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = $pid;
    //         $ret[ParamConstants::PARAMS][POP_INPUTNAME_NONCE] = gdCreateNonce(GD_NONCE_EDITURL, $pid);
    //     }
    //     if ($executed) {

    //         // Check if there are errors or if it was successful, and add corresponding messages.
    //         // $data = $this->getFormExecutedResponse($executed);
    //         if ($executed[ResponseConstants::SUCCESS]) {

    //             $pid = $dbobjectids[0];
    //             $nonce = $pid ? gdCreateNonce(GD_NONCE_EDITURL, $pid) : '';
    //             $ret[ParamConstants::PARAMS][\PoPCMSSchema\Posts\Constants\InputNames::POST_ID] = $pid;
    //             $ret[ParamConstants::PARAMS][POP_INPUTNAME_NONCE] = $nonce;
    //         }
    //     }

    //     return $ret;
    // }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_AddPost();
