<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\ItemListTableInterface;

abstract class AbstractItemListTableMenuPage extends AbstractTableMenuPage
{
    protected function createTableObject(): ItemListTableInterface
    {
        $tableObject = parent::createTableObject();

        /**
         * Set properties
         */
        $tableObject->setItemsPerPageOptionName($this->getScreenOptionName());
        $tableObject->setDefaultItemsPerPage($this->getScreenOptionDefault());
        
        return $tableObject;
    }
}
