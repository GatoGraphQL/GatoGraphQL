<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function hideAdminBarSettings()
{
    ?>
    <style type="text/css">
        .show-admin-bar {
            display: none;
        }
    </style>
    <?php
}
function disableAdminBar()
{
    \PoP\Root\App::getHookManager()->addFilter('show_admin_bar', '__return_false');
    \PoP\Root\App::getHookManager()->addAction('admin_print_scripts-profile.php', 'hideAdminBarSettings');
}
\PoP\Root\App::getHookManager()->addAction('init', 'disableAdminBar', 9);
