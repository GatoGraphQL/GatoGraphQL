<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AAL_PoP_API extends AAL_API
{

    // Override original function, so that it does nothing here for the notifications
    protected function _delete_old_items()
    {
        if (AAL_PoP_AdaptationUtils::swappedDbTable()) {
            // Do nothing
            return;
        }

        // Execute as planned by AAL
        parent::_delete_old_items();
    }

    // Override original function, so that it does nothing here for the notifications
    public function erase_all_items()
    {
        if (AAL_PoP_AdaptationUtils::swappedDbTable()) {
            // Do nothing
            return;
        }

        // Execute as planned by AAL
        parent::erase_all_items();
    }
}
