<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\Tables;

use GraphQLAPI\GraphQLAPI\App;
use WP_List_Table;

/**
 * Module Table
 */
abstract class AbstractItemListTable extends WP_List_Table
{
    protected string $itemsPerPageOptionName = '';
    protected int $defaultItemsPerPage = 10;

    public function setItemsPerPageOptionName(string $itemsPerPageOptionName): void
    {
        $this->itemsPerPageOptionName = $itemsPerPageOptionName;
    }
    public function setDefaultItemsPerPage(int $defaultItemsPerPage): void
    {
        $this->defaultItemsPerPage = $defaultItemsPerPage;
    }

    public function getItemsPerPageOptionName(): string
    {
        return $this->itemsPerPageOptionName;
    }
    public function getDefaultItemsPerPage(): int
    {
        return $this->defaultItemsPerPage;
    }

    /**
     * Singular name of the listed records
     */
    abstract public function getItemSingularName(): string;

    /**
     * Plural name of the listed records
     */
    abstract public function getItemPluralName(): string;

    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => $this->getItemSingularName(),
            'plural' => $this->getItemPluralName(),
            'ajax' => false,
        ]);

        \add_action(
            'admin_enqueue_scripts',
            [$this, 'enqueueAssets']
        );
        add_action(
            'admin_head',
            [$this, 'printStyles']
        );
    }

    /**
     * Print custom styles, such as the width of the columns
     */
    public function printStyles(): void
    {
        // Do nothing
    }

    /**
     * Enqueue the required assets
     */
    public function enqueueAssets(): void
    {
        $mainPluginURL = (string) App::getMainPluginManager()->getConfig('url');
        $mainPluginVersion = (string) App::getMainPluginManager()->getConfig('version');

        /**
         * Fix the issues with the WP List Table
         */
        \wp_enqueue_style(
            'graphql-api-wp-list-table-fix',
            $mainPluginURL . 'assets/css/wp-list-table-fix.css',
            array(),
            $mainPluginVersion
        );
    }

    /**
     * Text displayed when there are no items
     *
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function no_items()
    {
        _e('No items found.', 'graphql-api');
    }
}
