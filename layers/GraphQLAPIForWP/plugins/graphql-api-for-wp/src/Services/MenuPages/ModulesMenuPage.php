<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\Tables\ModuleListTable;

/**
 * Module22222 menu page
 */
class ModulesMenuPage extends AbstractTableMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

    public final const SCREEN_OPTION_NAME = 'graphql_api_modules_per_page';

    public function getMenuPageSlug(): string
    {
        return 'modules';
    }

    protected function getHeader(): string
    {
        return \__('GraphQL API — Modules', 'graphql-api');
    }

    protected function hasViews(): bool
    {
        return true;
    }

    protected function getScreenOptionLabel(): string
    {
        return \__('Modules', 'graphql-api');
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return !$this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getScreenOptionName(): string
    {
        return self::SCREEN_OPTION_NAME;
    }

    protected function getTableClass(): string
    {
        return ModuleListTable::class;
    }

    // protected function showScreenOptions(): bool
    // {
    //     return true;
    // }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueModalTriggerAssets();
    }
}
