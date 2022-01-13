<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElseWP\ConditionalOnComponent\CustomPosts\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeAPI extends \PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI
{
    public function getExcerpt(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        $readmore = sprintf(
            TranslationAPIFacade::getInstance()->__('... <a href="%s">Read more</a>', 'everythingelse-wp'),
            $this->getPermalink($customPostObjectOrID)
        );
        $value = empty($customPost->post_excerpt) ?
            \limitString(
                \strip_tags(
                    \strip_shortcodes($customPost->post_content)
                ),
                \getExcerptLength(),
                $readmore
            ) :
            $customPost->post_excerpt;
        return App::applyFilters(
            'get_the_excerpt',
            $value,
            $customPostID
        );
    }
}
