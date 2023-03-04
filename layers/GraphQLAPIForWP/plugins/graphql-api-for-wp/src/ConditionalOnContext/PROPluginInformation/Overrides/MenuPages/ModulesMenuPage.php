<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\Tables\AbstractItemListTable;
use GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Admin\Tables\ModuleListTable;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage as UpstreamModulesMenuPage;

class ModulesMenuPage extends UpstreamModulesMenuPage
{
    /**
     * @return class-string<AbstractItemListTable>
     */
    protected function getTableClass(): string
    {
        return ModuleListTable::class;
    }
}
