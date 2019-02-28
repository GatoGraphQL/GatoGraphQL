<?php
namespace PoP\Engine;

class HierarchyManager
{
    public static function getHierarchies()
    {

        /**
         * There are different hierarchies, where each one can have its own set of blocks/actions
         * Eg: Author hierarchy will filter its posts by the corresponding author / Send 'Contact Profile' to that author without the need to send the user_id
         */
        return array(
        GD_SETTINGS_HIERARCHY_PAGE,
        GD_SETTINGS_HIERARCHY_AUTHOR,
        GD_SETTINGS_HIERARCHY_HOME,
        GD_SETTINGS_HIERARCHY_TAG,
        GD_SETTINGS_HIERARCHY_SINGLE,
        GD_SETTINGS_HIERARCHY_404,
        );
    }
}
