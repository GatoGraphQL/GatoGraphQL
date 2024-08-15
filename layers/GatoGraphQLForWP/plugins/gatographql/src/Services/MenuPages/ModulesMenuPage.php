<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\ItemListTableInterface;
use GatoGraphQL\GatoGraphQL\Admin\Tables\ModuleListTable;

/**
 * Module menu page
 */
class ModulesMenuPage extends AbstractItemListTableMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

    public final const SCREEN_OPTION_NAME = 'gatographql_modules_per_page';

    public function getMenuPageSlug(): string
    {
        return 'modules';
    }

    protected function getHeader(): string
    {
        return \__('Gato GraphQL — Modules', 'gatographql');
    }

    protected function hasViews(): bool
    {
        return true;
    }

    protected function getScreenOptionLabel(): string
    {
        return \__('Modules', 'gatographql');
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        if (!parent::isCurrentScreen()) {
            return false;
        }
        return !$this->getMenuPageHelper()->isDocumentationScreen();
    }

    protected function getScreenOptionName(): string
    {
        return self::SCREEN_OPTION_NAME;
    }

    /**
     * @return class-string<ItemListTableInterface>
     */
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
