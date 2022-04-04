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
    public final const TAB = 'tab';
    public final const TAB_DOCS = 'docs';
    public final const MODULE = 'module';
    public final const DOC = 'doc';
    public final const PERSISTED_QUERY_ID = 'persisted_query_id';
    public final const BEHAVIOR = 'behavior';
    public final const BEHAVIOR_UNRESTRICTED = 'unrestricted';
}
