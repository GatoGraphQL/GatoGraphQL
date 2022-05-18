<?php
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;

abstract class PoP_Module_Processor_ListFeedbackMessageInnersBase extends PoP_Module_Processor_FeedbackMessageInnersBase
{

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        
        // Show error message if no items, but only if the checkpoint did not fail
        // Do not show the message when doing loadLatest
        $checkpoint_failed = $dataaccess_checkpoint_validation !== null;
        if (!$checkpoint_failed && empty($dbobjectids) && (!\PoP\Root\App::hasState('loading-latest') || !\PoP\Root\App::getState('loading-latest'))) {
            $query_args = $data_properties[DataloadingConstants::QUERYARGS];
            $pagenumber = $query_args[\PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER];

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
