<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use WP_Post;

interface ClientEndpointAnnotatorInterface extends EndpointAnnotatorInterface
{
    public function isClientEnabled(WP_Post|int $postOrID): bool;
}
