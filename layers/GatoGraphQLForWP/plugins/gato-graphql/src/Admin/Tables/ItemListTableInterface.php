<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

interface ItemListTableInterface
{
    /**
     * Displays the list of views available on this table.
     * @return void
     */
    public function views();

    /**
     * Prepares the list of items for displaying.
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function prepare_items();

    /**
     * Displays the table.
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function display();

    public function setItemsPerPageOptionName(string $itemsPerPageOptionName): void;
    public function setDefaultItemsPerPage(int $defaultItemsPerPage): void;
}
