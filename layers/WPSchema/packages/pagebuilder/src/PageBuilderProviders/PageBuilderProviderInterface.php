<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\PageBuilderProviders;

use PoP\Root\Services\ActivableServiceInterface;

interface PageBuilderProviderInterface extends ActivableServiceInterface
{
    /**
     * The unique slug identifying the provider.
     *
     * It must contain only chars allowed in a GraphQL enum type.
     */
    public function getName(): string;

    /**
     * The name of the provider
     */
    public function getProviderName(): string;

    /**
     * The names of the CPTs edited using this Page Builder
     *
     * @return string[]
     */
    public function getEnabledCustomPostTypes(): array;
}
