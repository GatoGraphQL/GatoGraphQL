<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

interface ItemListTableInterface
{
    /**
	 * Displays the list of views available on this table.
	 *
	 * @since 3.1.0
	 */
	public function views();
    /**
	 * Prepares the list of items for displaying.
	 *
	 * @uses WP_List_Table::set_pagination_args()
	 *
	 * @since 3.1.0
	 * @abstract
	 */
	public function prepare_items();
    /**
	 * Displays the table.
	 *
	 * @since 3.1.0
	 */
	public function display();


    public function setItemsPerPageOptionName(string $itemsPerPageOptionName): void;
    public function setDefaultItemsPerPage(int $defaultItemsPerPage): void;


    // public function getItemsPerPageOptionName(): string;
    // public function getDefaultItemsPerPage(): int;

    // // /**
    // //  * Singular name of the listed records
    // //  */
    // // public function getItemSingularName(): string;

    // // /**
    // //  * Plural name of the listed records
    // //  */
    // // public function getItemPluralName(): string;

    // /**
    //  * Print custom styles, such as the width of the columns
    //  */
    // public function printStyles(): void;

    // /**
    //  * Enqueue the required assets
    //  */
    // public function enqueueAssets(): void;
}
