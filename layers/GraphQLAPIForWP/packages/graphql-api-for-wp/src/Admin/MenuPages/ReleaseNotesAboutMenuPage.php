<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

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

    protected function getRelativePathDir(): string
    {
        return 'release-notes';
    }
}
