<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_ListFeedbackMessageInnersBase extends PoP_Module_Processor_FeedbackMessageInnersBase
{

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        $vars = ApplicationState::getVars();

        // Show error message if no items, but only if the checkpoint did not fail
        // Do not show the message when doing loadLatest
        $checkpoint_failed = GeneralUtils::isError($dataaccess_checkpoint_validation);
        if (!$checkpoint_failed && empty($dbobjectids) && (!isset($vars['loading-latest']) || !$vars['loading-latest'])) {
            $query_args = $data_properties[DataloadingConstants::QUERYARGS];
            $pagenumber = $query_args[\PoP\ComponentModel\Constants\Params::PAGE_NUMBER];

            // If pagenumber < 2 => There are no results at all
            $msg = array(
                'codes' => array(
                    ($pagenumber < 2) ? 'noresults' : 'nomore',
                ),
            );
            if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                $msg[GD_JS_CLASS] = 'alert-warning';
            }
            $ret['msgs'] = array(
                $msg
            );
        }

        return $ret;
    }
}
