<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Constants;

class RequestParams
{
    public final const VIEW = 'view';
    public final const VIEW_SOURCE = 'source';
    public final const VIEW_GRAPHIQL = 'graphiql';
    public final const VIEW_SCHEMA = 'schema';
    public final const ACTION = 'action';
    public final const ACTION_EXECUTE_QUERY = 'execute_query';
    public final const CATEGORY = 'category';
    public final const TAB = 'tab';
    public final const TAB_DOCS = 'docs';
    public final const MODULE = 'module';
    public final const DOC = 'doc';
    public final const PERSISTED_QUERY_ID = 'persisted_query_id';

    /**
     * Param used to obtain the configuration to apply to the requested
     * admin endpoint, based on an "endpointGroup".
     *
     * For instance, this plugin defines the configuration endpointGroup
     * "pluginOwnUse" to be used on the WordPress editor to
     * power this plugin's blocks. It shall be requested as:
     *
     *   /wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=pluginOwnUse
     *
     * If the endpointGroup is not provided, the default admin endpoint
     * configuration is applied.
     */
    public final const ENDPOINT_GROUP = 'endpoint_group';
}
