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
    HooksAPIFacade::getInstance()->addFilter('show_admin_bar', '__return_false');
    HooksAPIFacade::getInstance()->addAction('admin_print_scripts-profile.php', 'hideAdminBarSettings');
}
HooksAPIFacade::getInstance()->addAction('init', 'disableAdminBar', 9);
