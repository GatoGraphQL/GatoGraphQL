<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use WP_Plugin_Install_List_Table;

/**
 * Extension Table
 */
class ExtensionListTable extends WP_Plugin_Install_List_Table implements ItemListTableInterface
{
    use ItemListTableTrait;
}
