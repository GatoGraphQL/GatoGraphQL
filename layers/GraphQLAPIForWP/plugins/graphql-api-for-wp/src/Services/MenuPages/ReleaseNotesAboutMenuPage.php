<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

/**
 * Release notes menu page
 */
class ReleaseNotesAboutMenuPage extends AbstractDocAboutMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        MenuPageHelper $menuPageHelper,
        EndpointHelpers $endpointHelpers,
        protected AboutMenuPage $aboutMenuPage,
    ) {
        parent::__construct(
            $instanceManager,
            $menuPageHelper,
            $endpointHelpers
        );
    }

    public function getMenuPageSlug(): string
    {
        return $this->aboutMenuPage->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->menuPageHelper->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getRelativePathDir(): string
    {
        return 'release-notes';
    }
}
