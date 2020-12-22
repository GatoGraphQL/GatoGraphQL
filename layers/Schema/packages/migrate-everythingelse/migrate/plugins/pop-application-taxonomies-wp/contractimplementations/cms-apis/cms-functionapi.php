<?php

namespace PoP\ApplicationTaxonomies\WP;

use PoPSchema\EverythingElse\Misc\TagHelpers;

class FunctionAPI extends \PoP\ApplicationTaxonomies\FunctionAPI_Base
{
    protected function getTagObjectAndID($tagObjectOrID): array
    {
        if (is_object($tagObjectOrID)) {
            $tag = $tagObjectOrID;
            $tagID = $tag->term_id;
        } else {
            $tagID = $tagObjectOrID;
            $tag = \get_tag($tagID);
        }
        return [
            $tag,
            $tagID,
        ];
    }
    public function getTagSymbolName($tagObjectOrID)
    {
        list(
            $tag,
            $tagID,
        ) = $this->getTagObjectAndID($tagObjectOrID);
        $posttagapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        return TagHelpers::getTagSymbol() . $posttagapi->getTagName($tagID);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
