<?php

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_CheckpointMessageInnersBase extends PoP_Module_Processor_FeedbackMessageInnersBase /*PoP_Module_Processor_StructureInnersBase*/
{

    // function getTemplate(array $module, array &$props) {

    //     // return POP_TEMPLATE_CHECKPOINTMESSAGE_INNER;
    //     return POP_TEMPLATE_FEEDBACKMESSAGE_INNER;
    // }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        // Checkpoint validation required?
        if ($data_properties[\PoP\ComponentModel\Constants\DataLoading::DATA_ACCESS_CHECKPOINTS] && GeneralUtils::isError($dataaccess_checkpoint_validation)) {
            $msg = array(
                'codes' => array(
                    $dataaccess_checkpoint_validation->getFeedbackItemProvider()->getNamespacedCode()
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
                $msg,
            );
        }

        return $ret;
    }
}
