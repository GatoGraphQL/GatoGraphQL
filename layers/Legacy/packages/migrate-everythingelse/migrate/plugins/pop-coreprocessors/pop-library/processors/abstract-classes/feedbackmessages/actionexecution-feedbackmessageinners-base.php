<?php
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase extends PoP_Module_Processor_FeedbackMessageInnersBase
{

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        // Feedback comes from the Action Execution response
        // If $executed != null, then $checkpoint succeded, no need to ask for this condition before printing the messages
        if ($data_properties[\PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS] && GeneralUtils::isError($actionexecution_checkpoint_validation)) {
            $msg = array(
                'codes' => array(
                    $actionexecution_checkpoint_validation->getCode()
                ),
                'header' => array(
                    'code' => 'error-header',
                ),
            );
            if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                $msg[GD_JS_CLASS] = 'alert-warning checkpoint';
                $msg['icon'] = 'glyphicon-warning-sign';
            }
            $ret['msgs'] = array(
                $msg
            );
        } elseif ($executed) {
            // Check if there are errors or if it was successful, and add corresponding messages.
            // $data = $this->getFormExecutedResponse($executed);

            // Allow for multiple messages. Eg: invite members email, send whenever email is valid, error when invalid
            $msgs = array();
            if ($executed[ResponseConstants::SUCCESS]) {
                $msg = array(
                    'header' => array(
                        'code' => 'success-header',
                    ),
                    'footer' => array(
                        'code' => 'success-footer',
                    ),
                    'content' => array(
                        'code' => 'content',
                    ),
                );
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                    $msg[GD_JS_CLASS] = 'alert-success';
                    $msg['icon'] = 'glyphicon-ok';
                }
                if ($success_strings = $executed[ResponseConstants::SUCCESSSTRINGS]) {
                    $msg['strings'] = $success_strings;
                } else {
                    $msg['codes'] = array('success');
                }

                $msgs[] = $msg;
                // $ret['result'] = true;
            }
            if ($executed[ResponseConstants::ERRORCODES] || $executed[ResponseConstants::ERRORSTRINGS]) {
                $msg = array(
                    'header' => array(
                        'code' => 'error-header',
                    ),
                    'strings' => $executed[ResponseConstants::ERRORSTRINGS],
                    'codes' => $executed[ResponseConstants::ERRORCODES],
                );
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                    $msg[GD_JS_CLASS] = 'alert-danger';
                    $msg['icon'] = 'glyphicon-remove';
                }

                // Add the error message before the success
                array_unshift($msgs, $msg);
                // $ret['result'] = false;
            }

            if ($msgs) {
                $ret['msgs'] = $msgs;
            }

            // // Redirect?
            // if ($redirect = $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT]) {
            //     $ret['redirect'] = array(
            //         'url' => $redirect,
            //         'fetch' => true
            //     );
            // }
            // elseif ($redirect = $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_HARDREDIRECT]) {
            //     $ret['redirect'] = array(
            //         'url' => $redirect,
            //         'fetch' => false
            //     );
            // }
        }

        return $ret;
    }

    // // By default, assume that $executed will already be the response needed as in function get_feedback above
    // function getFormExecutedResponse($executed) {

    //     return $executed;
    // }
}
