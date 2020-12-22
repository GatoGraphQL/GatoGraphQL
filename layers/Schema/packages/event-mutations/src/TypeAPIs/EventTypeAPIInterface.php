<?php

declare(strict_types=1);

namespace PoPSchema\EventMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface EventMutationTypeAPIInterface
{
    public function populate(object &$event, array $post_data): void;
}
