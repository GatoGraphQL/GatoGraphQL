<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Release notes menu page
 */
class ReleaseNotesAboutMenuPage extends AbstractDocAboutMenuPage
{
    public function getMenuPageSlug(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var AboutMenuPage
         */
        $modulesMenuPage = $instanceManager->getInstance(AboutMenuPage::class);
        return $modulesMenuPage->getMenuPageSlug();
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
