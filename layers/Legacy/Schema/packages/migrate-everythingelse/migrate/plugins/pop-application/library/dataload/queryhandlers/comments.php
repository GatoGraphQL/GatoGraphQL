<?php
use PoP\Application\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;

class GD_DataLoad_QueryInputOutputHandler_CommentList extends ListQueryInputOutputHandler
{
    public function prepareQueryArgs(&$query_args)
    {
        parent::prepareQueryArgs($query_args);

        if (!isset($query_args[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID])) {
            $vars = ApplicationState::getVars();

            // By default, select the global $post ID;
            $query_args[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID] = $vars['routing']['queried-object-id'];
        }

        // // Limit: by default, show all comments
        // $query_args[\PoP\ComponentModel\Constants\PaginationParams::LIMIT] = $query_args[\PoP\ComponentModel\Constants\PaginationParams::LIMIT] ?? '';

        // The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
        $query_args['order'] =  'ASC';
        $query_args['orderby'] = NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:comments:date');
    }

    // function getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $query_args = $data_properties[ParamConstants::QUERYARGS];

    //     // Add the post_id, so we know what post to fetch comments from when filtering
    //     $ret[ParamConstants::PARAMS][\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID] = $query_args[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID];

    //     return $ret;
    // }

    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // Add the post_id, so we know what post to fetch comments from when filtering
        $ret[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID] = $query_args[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID];

        return $ret;
    }

    // function getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $query_args = $data_properties[ParamConstants::QUERYARGS];

    //     // Add the post_id, so we know what post to fetch comments from when filtering
    //     $ret[ParamConstants::PARAMS][\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID] = $query_args[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID];

    //     return $ret;
    // }
}

/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_CommentList();
