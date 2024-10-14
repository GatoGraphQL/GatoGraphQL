<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

trait ItemListTableTrait
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
}
