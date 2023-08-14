<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\PluginApp;
use WP_List_Table;

/**
 * Module Table
 */
abstract class AbstractItemListTable extends WP_List_Table implements ItemListTableInterface
{
    use ItemListTableTrait;

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
            $this->enqueueAssets(...)
        );
        add_action(
            'admin_head',
            $this->printStyles(...)
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
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Fix the issues with the WP List Table
         */
        \wp_enqueue_style(
            'gatographql-wp-list-table-fix',
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
        _e('No items found.', 'gatographql');
    }

    /**
     * Gets a list of CSS classes for the WP_List_Table table tag.
     *
     * @since 3.1.0
     *
     * @return string[] Array of CSS classes for the table tag.
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function get_table_classes()
    {
        return array_merge(
            parent::get_table_classes(),
            [
                'gatographql-list-table',
            ]
        );
    }
}
