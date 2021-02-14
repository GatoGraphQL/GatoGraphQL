<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\General\RequestParams;
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
        return (isset($_GET[RequestParams::TAB]) && $_GET[RequestParams::TAB] == RequestParams::TAB_DOCS) && parent::isCurrentScreen();
    }

    protected function getRelativePathDir(): string
    {
        return 'release-notes';
    }
}
