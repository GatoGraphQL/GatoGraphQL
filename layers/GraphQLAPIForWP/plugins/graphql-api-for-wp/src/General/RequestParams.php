<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

class RequestParams
{
    public const VIEW = 'view';
    public const VIEW_SOURCE = 'source';
    public const VIEW_GRAPHIQL = 'graphiql';
    public const VIEW_SCHEMA = 'schema';
    public const ACTION = 'action';
    public const ACTION_EXECUTE_QUERY = 'execute_query';
    public const TAB = 'tab';
    public const TAB_DOCS = 'docs';
    public const MODULE = 'module';
    public const DOC = 'doc';
    public const PERSISTED_QUERY_ID = 'persisted_query_id';
}
