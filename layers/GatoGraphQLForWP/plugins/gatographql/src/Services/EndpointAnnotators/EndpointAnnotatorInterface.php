<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators;

use PoP\Root\Services\ActivableServiceInterface;
use WP_Post;

interface EndpointAnnotatorInterface extends ActivableServiceInterface
{
    /**
     * Add actions to the CPT list
     * @param array<string,string> $actions
     */
    public function addCustomPostTypeTableActions(array &$actions, WP_Post $post): void;
}
