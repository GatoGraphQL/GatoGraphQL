<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

interface ItemListTableInterface extends TableInterface
{
    public function setItemsPerPageOptionName(string $itemsPerPageOptionName): void;
    public function setDefaultItemsPerPage(int $defaultItemsPerPage): void;
}
