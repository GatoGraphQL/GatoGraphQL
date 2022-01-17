<?php
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;

class GD_DataLoad_QueryInputOutputHandler_EditMembership extends ActionExecutionQueryInputOutputHandler
{
    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        $uid = $_REQUEST[\PoPCMSSchema\Users\Constants\InputNames::USER_ID] ?? null;
        $ret[\PoPCMSSchema\Users\Constants\InputNames::USER_ID] = $uid;
        $ret[POP_INPUTNAME_NONCE] = gdCreateNonce(GD_NONCE_EDITMEMBERSHIPURL, $uid);

        return $ret;
    }

    // function getSharedbydomainsQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getSharedbydomainsQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $uid = $_REQUEST[\PoPCMSSchema\Users\Constants\InputNames::USER_ID];
    //     $ret[ParamConstants::PARAMS][\PoPCMSSchema\Users\Constants\InputNames::USER_ID] = $uid;
    //     $ret[ParamConstants::PARAMS][POP_INPUTNAME_NONCE] = gdCreateNonce(GD_NONCE_EDITMEMBERSHIPURL, $uid);

    //     return $ret;
    // }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_EditMembership();
