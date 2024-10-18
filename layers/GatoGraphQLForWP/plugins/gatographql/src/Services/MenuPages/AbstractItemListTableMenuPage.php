<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\ItemListTableInterface;
use GatoGraphQL\GatoGraphQL\Admin\Tables\TableInterface;

abstract class AbstractItemListTableMenuPage extends AbstractTableMenuPage
{
    /**
     * Redefine the class of the table.
     *
     * @return class-string<ItemListTableInterface>
     */
    abstract protected function getTableClass(): string;

    /**
     * Redefine the class of the table.
     *
     * @return ItemListTableInterface
     */
    protected function createTableObject(): TableInterface
    {
        /** @var ItemListTableInterface */
        $tableObject = parent::createTableObject();

        /**
         * Set properties
         */
        $tableObject->setItemsPerPageOptionName($this->getScreenOptionName());
        $tableObject->setDefaultItemsPerPage($this->getScreenOptionDefault());

        return $tableObject;
    }
}
