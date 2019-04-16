<?php
namespace PoP\Engine;

class NatureManager
{
    public static function getNatures()
    {

        /**
         * There are different natures, where each one can have its own set of blocks/actions
         * Eg: Author nature will filter its posts by the corresponding author / Send 'Contact Profile' to that author without the need to send the user_id
         */
        return array(
            POP_NATURE_STANDARD,
            POP_NATURE_HOME,
            POP_NATURE_404,
            POP_NATURE_PAGE,
            POP_NATURE_SINGLE,
            POP_NATURE_AUTHOR,
            POP_NATURE_TAG,
            // POP_NATURE_CATEGORY,
        );
    }
}
