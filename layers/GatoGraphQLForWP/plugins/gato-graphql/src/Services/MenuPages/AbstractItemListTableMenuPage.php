<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\ItemListTableInterface;

abstract class AbstractItemListTableMenuPage extends AbstractTableMenuPage
{
    /**
     * Redefine the class of the table
     * @return class-string<ItemListTableInterface>
     */
    abstract protected function getTableClass(): string;

    protected function createTableObject(): ItemListTableInterface
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
