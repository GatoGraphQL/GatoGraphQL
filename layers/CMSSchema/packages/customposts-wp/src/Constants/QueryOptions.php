<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Constants;

class QueryOptions
{
    /**
     * Option to tell the hook to not remove the private CPTs when querying
     */
    public final const ALLOW_QUERYING_PRIVATE_CPTS = 'allow-querying-private-cpts';
}
