<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryInputOutputHandlers;

use PoP\Root\Feedback\FeedbackItemResolution;

class ActionExecutionQueryInputOutputHandler extends AbstractQueryInputOutputHandler
{
    public function getQueryResult(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($executed) {
            // $executed may contain strings "success", "successstrings", "softredirect", etc
            $ret = array_merge(
                $ret,
                $executed
            );
        }

        return $ret;
    }
}
