<?php
namespace PoP\Engine\Impl;

define('GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION', 'actionexecution');

class QueryHandler_ActionExecution extends \PoP\Engine\QueryHandlerBase
{
    public function getName()
    {
        return GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION;
    }

    public function getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        $ret = parent::getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($executed) {
            $ret['success'] = $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESS] ? true : false;

            // Redirect?
            if ($redirect = $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT]) {
                $ret['redirect'] = array(
                    'url' => $redirect,
                    'fetch' => true
                );
            } elseif ($redirect = $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_HARDREDIRECT]) {
                $ret['redirect'] = array(
                    'url' => $redirect,
                    'fetch' => false
                );
            }
        }

        return $ret;
    }

    // function getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {
    
    //     $ret = parent::getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     // Feedback comes from the Action Execution response
    //     // If $executed != null, then $checkpoint succeded, no need to ask for this condition before printing the messages
    //     if ($executed) {

    //         // Check if there are errors or if it was successful, and add corresponding messages.
    //         $data = $this->getFormExecutedResponse($executed);

    //         // Allow for multiple messages. Eg: invite members email, send whenever email is valid, error when invalid
    //         $msgs = array();
    //         if ($data[GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESS]) {

    //             $msg = array(
    //                 'icon' => 'glyphicon-ok',
    //                 GD_JS_CLASS => 'alert-success',
    //                 'header' => array(
    //                     'code' => 'success-header',
    //                 ),
    //                 'footer' => array(
    //                     'code' => 'success-footer',
    //                 ),
    //                 'content' => array(
    //                     'code' => 'content',
    //                 ),
    //             );
    //             if ($success_strings = $data[GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESSSTRINGS]) {
    //                 $msg['strings'] = $success_strings;
    //             }
    //             else {
    //                 $msg['codes'] = array('success');
    //             }

    //             $msgs[] = $msg;
    //             $ret['result'] = true;
    //         }
    //         if ($data[GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORCODES] || $data[GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORSTRINGS]) {
            
    //             $msg = array(
    //                 'icon' => 'glyphicon-remove',
    //                 'header' => array(
    //                     'code' => 'error-header',
    //                 ),
    //                 'strings' => $data[GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORSTRINGS],
    //                 'codes' => $data[GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORCODES],
    //                 GD_JS_CLASS => 'alert-danger',
    //             );

    //             // Add the error message before the success
    //             array_unshift($msgs, $msg);
    //             $ret['result'] = false;
    //         }
    //         $ret['msgs'] = $msgs;

    //         // Redirect?
    //         if ($redirect = $data[GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT]) {
    //             $ret['redirect'] = array(
    //                 'url' => $redirect,
    //                 'fetch' => true
    //             );
    //         }
    //         elseif ($redirect = $data[GD_DATALOAD_QUERYHANDLERRESPONSE_HARDREDIRECT]) {
    //             $ret['redirect'] = array(
    //                 'url' => $redirect,
    //                 'fetch' => false
    //             );
    //         }
    //     }
        
    //     return $ret;
    // }

    // // By default, assume that $executed will already be the response needed as in function get_feedback above
    // function getFormExecutedResponse($executed) {
    
    //     return $executed;
    // }
}
    
/**
 * Initialize
 */
new QueryHandler_ActionExecution();
