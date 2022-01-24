<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\SystemServices\TableActions;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Table Action
 */
abstract class AbstractListTableAction extends AbstractAutomaticallyInstantiatedService
{
    /**
     * Get the current action selected from the bulk actions dropdown.
     *
     * Function copied from wp-admin/includes/class-wp-list-table.php function `current_action`
     *
     * @since 3.1.0
     *
     * @return string|false The action name or False if no action was selected
     * phpcs:disable Generic.PHP.DisallowRequestSuperglobal
     */
    public function currentAction(): string|false
    {
        if (isset($_REQUEST['filter_action']) && ! empty($_REQUEST['filter_action'])) {
            return false;
        }

        if (isset($_REQUEST['action']) && -1 != $_REQUEST['action']) {
            return $_REQUEST['action'];
        }

        if (isset($_REQUEST['action2']) && -1 != $_REQUEST['action2']) {
            return $_REQUEST['action2'];
        }

        return false;
    }
}
