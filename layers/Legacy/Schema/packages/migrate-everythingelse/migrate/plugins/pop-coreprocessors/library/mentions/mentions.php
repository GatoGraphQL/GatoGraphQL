<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoPSchema\Comments\Facades\CommentTypeAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

/**
 * Copied from plugin `hashtagger` (https://wordpress.org/plugins/hashtagger/)
 * Extracts #hashtags from the post and adds them as tags
 * Extracts @user_nicenames from the post and adds a notification for that user
 */
class PoP_Mentions
{
    protected $regex_general;
    protected $regex_users;

    public function __construct()
    {

        // $this->regex_users =    '/(^|[\s!\.:;\?(>])*@([\p{L}][\p{L}0-9_]+)(?=[^<>]*(?:<|$))/u';
        // $this->regex_users =    '/(^|[\s!\.:;\?(>])\@([\p{L}][\p{L}0-9_]+)(?=[^<>]*(?:<|$))/u';

        // Allow also underscore ("_")
        // Regex taken from https://stackoverflow.com/questions/13639478/how-do-i-extract-words-starting-with-a-hash-tag-from-a-string-into-an-array
        // $this->regex_general =  '/(?<!\w)#([A-Za-z0-9_]+)/u';
        // Regex taken from Twitter Hashtag validator: https://gist.github.com/janogarcia/3946583
        // Explanation:
        // * A hashtag can contain any UTF-8 alphanumeric character, plus the underscore symbol. That's expressed with the character class [0-9_\p{L}]*, based on http://stackoverflow.com/a/5767106/1441613
        // * A hashtag can't be only numeric, it must have at least one alpahanumeric character or the underscore symbol. That condition is checked by ([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*), similar to http://stackoverflow.com/a/1051998/1441613
        // * Finally, the modifier 'u' is added to ensure that the strings are treated as UTF-8
        // $this->regex_general =  '/(?<!\w)#([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*)/u';
        // Solution taken from a combination of:
        // 1. https://stackoverflow.com/questions/1563844/best-hashtag-regex:
        // Use: '/(?<=\s|^)#(\w*[A-Za-z_]+\w*)/';
        // 2. https://stackoverflow.com/questions/37920638/regex-pattern-to-match-hashtag-but-not-in-html-attributes
        // "Regex pattern to match hashtag, but not in HTML attributes"
        // /#[a-z0-9_]+(?![^<]*>)/
        // What the negative lookahead does is makes sure that there is a < between the hashtag and the next >.
        // So then I took the regex from #1, and applied the negative lookahead: +(?![^<]*>)
        // Comment Leo 20/12/2016: Also added characther \x{A0} (https://unicodelookup.com/# /1) in addition to \s, since it appears sometimes between hashtags and would not be recognized
        $this->regex_general =  '/(?<=\s|\x{A0}|^)#(\w*[A-Za-z_]+\w*)+(?![^<]*>)/';
        // // Comment Leo 06/03/2019: Changed the regex again, to work inside HTML, so it works after doing wpautop (because this hook is now executing after doing wpautop, can't do before anymore since abstracting the CMS and first calling $customPostTypeAPI->getContent)
        // $this->regex_general =  '/(?:\s|^|\x{A0}|>)(#[A-Za-z0-9\-\.\_]+)(?:\s|$)(?=[^>]*(<|$))/';


        // Allow also underscore ("_"), dash ("-") and dots ("."), but only when they are not the final char (@pedro.perez. = pedro.perez). Eg: greenpeace-asia
        // Regex taken from https://stackoverflow.com/questions/13639478/how-do-i-extract-words-starting-with-a-hash-tag-from-a-string-into-an-array
        $this->regex_users =    '/(?<!\w)@([a-z0-9-._]+[a-z0-9])/iu';

        // Save the tags immediately
        \PoP\Root\App::addAction(
            'popcms:savePost',
            array($this, 'generatePostTags'),
            0
        );
        \PoP\Root\App::addAction(
            'popcms:insertComment',
            array($this, 'generateCommentTags'),
            0,
            2
        );

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            // Can't use filter "the_content" because it doesn't work with page How to use website on MESYM
            // So quick fix: ignore for pages. Since the_content does not pass the post_id, we use another hook
            // Execute before wpautop, or otherwise the hashtags after <p> don't work
            \PoP\Root\App::addFilter('pop_content', array($this, 'processContentPost'), 5, 2);

            // Comment Leo 08/05/2016: Do not enable for excerpts, because somehow sometimes it fails (eg: with MESYM Documentary Night event) deleting everything
            // \PoP\Root\App::addFilter('pop_excerpt', array($this, 'processContentPost'), 9999, 2);
            // Execute before wpautop
            \PoP\Root\App::addFilter('gd_comments_content', array($this, 'processContent'), 5);
        }
    }

    // this function extracts the hashtags from content and adds them as tags to the post
    public function generatePostTags($post_id)
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            $content = $customPostTypeAPI->getContent($post_id);

            // $append = true because we will also add tags to this post extracted from comments posted under this post
            $tags = $this->getHashtagsFromContent($content);
            $postTagTypeAPI->setPostTags($post_id, $tags, true);

            // Allow Events Manager to also add its own tags with its own taxonomy
            // This is needed so we can search using parameter 'tag' with events, using the common slug
            \PoP\Root\App::doAction('PoP_Mentions:post_tags:add', $post_id, $tags);

            // Extract all user_nicenames and notify them they were tagged
            // Get the previous ones, as to send an email only to the new ones
            $previous_taggedusers_ids = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($post_id, GD_METAKEY_POST_TAGGEDUSERS);

            // First delete all existing users, then add all new ones
            \PoPSchema\CustomPostMeta\Utils::deleteCustomPostMeta($post_id, GD_METAKEY_POST_TAGGEDUSERS);
            if ($user_nicenames = $this->getUserNicenamesFromContent($content)) {
                $taggedusers_ids = array();
                foreach ($user_nicenames as $user_nicename) {
                    if ($user = $cmsusersapi->getUserBySlug($user_nicename)) {
                        $taggedusers_ids[] = $userTypeAPI->getUserId($user);
                    }
                }

                if ($taggedusers_ids) {
                    \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_TAGGEDUSERS, $taggedusers_ids);

                    // Send an email to all newly tagged users
                    if ($newly_taggedusers_ids = array_diff($taggedusers_ids, $previous_taggedusers_ids)) {
                        \PoP\Root\App::doAction('PoP_Mentions:post_tags:tagged_users', $post_id, $taggedusers_ids, $newly_taggedusers_ids);
                    }
                }
            }
        }
    }

    public function generateCommentTags($comment_id, $comment)
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $commentTypeAPI = CommentTypeAPIFacade::getInstance();
        if ($tags = $this->getHashtagsFromContent($commentTypeAPI->getCommentContent($comment))) {
            // $append = true because the tags are added to the post from the comment
            $postTagTypeAPI->setPostTags($commentTypeAPI->getCommentPostId($comment), $tags, true);

            // Save the tags as comment meta
            $tag_ids = $postTagTypeAPI->getTags(
                array(
                    // 'fields' => 'ids',
                    'slugs' => $tags,
                ),
                [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
            );
            \PoPSchema\CommentMeta\Utils::updateCommentMeta($comment_id, GD_METAKEY_COMMENT_TAGS, $tag_ids);
        }

        // Allow Events Manager to also add its own tags with its own taxonomy
        // This is needed so we can search using parameter 'tag' with events, using the common slug
        \PoP\Root\App::doAction('PoP_Mentions:post_tags:add', $commentTypeAPI->getCommentPostId($comment), $tags);

        if ($user_nicenames = $this->getUserNicenamesFromContent($commentTypeAPI->getCommentContent($comment))) {
            $taggedusers_ids = array();
            foreach ($user_nicenames as $user_nicename) {
                if ($user = $cmsusersapi->getUserBySlug($user_nicename)) {
                    $taggedusers_ids[] = $userTypeAPI->getUserId($user);
                }
            }

            if ($taggedusers_ids) {
                \PoPSchema\CommentMeta\Utils::updateCommentMeta($comment_id, GD_METAKEY_COMMENT_TAGGEDUSERS, $taggedusers_ids);

                // Send an email to all newly tagged users
                \PoP\Root\App::doAction('PoP_Mentions:comment_tags:tagged_users', $comment_id, $taggedusers_ids);
            }
        }
    }

    // this function returns an array of hashtags from a given content - used by generate_tags()
    public function getHashtagsFromContent($content)
    {
        preg_match_all($this->regex_general, $content, $matches);
        return $matches[1];
    }

    // this function returns an array of user_nicenames from a given content - used by generate_tags()
    public function getUserNicenamesFromContent($content)
    {
        preg_match_all($this->regex_users, $content, $matches);
        return $matches[1];
    }

    // general function to process content
    public function work($content)
    {

        // Tags
        $content = str_replace('##', '#', preg_replace_callback($this->regex_general, array( $this, 'makeLinkTag' ), $content));

        // Usernames
        $content = str_replace('@@', '@', preg_replace_callback($this->regex_users, array( $this, 'makeLinkUserNicenames' ), $content));

        return $content;
    }

    // replace hashtags with links when displaying content
    // since v 3.0 post type depending
    public function processContent($content)
    {
        $content = $this->work($content);
        return $content;
    }

    public function processContentPost($content, $post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            $content = $this->work($content);
        }
        return $content;
    }

    // callback functions for preg_replace_callback used in content()
    public function makeLinkTag($match)
    {
        return $this->makeLink($match);
    }
    public function makeLinkUserNicenames($match)
    {
        return $this->makeLinkUsers($match);
    }

    // function to generate tag link
    private function makeLink($match)
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $cmstagresolver = PostTagTypeAPIFacade::getInstance();
        $tag = $postTagTypeAPI->getTagByName($match[1]);
        if (!$tag) {
            $content = $match[0];
        } else {
            $content = sprintf(
                '<a class="hashtagger-tag" href="%s">%s</a>',
                $postTagTypeAPI->getTagURL($cmstagresolver->getTagID($tag)),
                $match[0]
            );
        }

        return $content;
    }

    // function to generate user link
    private function makeLinkUsers($match)
    {
		$cmsService = CMSServiceFacade::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        // get by nickname or by login name
        $user = $cmsusersapi->getUserBySlug($match[1]);
        if (!$user) {
            $content = $match[0];
        } else {
            // Allow for the popover by adding data-popover-id
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var RelationalTypeResolverInterface */
            $userObjectTypeResolver = $instanceManager->getInstance(UserObjectTypeResolver::class);
            $content = sprintf(
                '<a class="pop-mentions-user" data-popover-target="%s" href="%s">%s</a>',
                '#popover-' . RequestUtils::getDomainId($cmsService->getSiteURL()) . '-' . $userObjectTypeResolver->getTypeName() . '-' . $userTypeAPI->getUserId($user),
                $userTypeAPI->getUserURL($userTypeAPI->getUserId($user)),
                $userTypeAPI->getUserDisplayName($user)
            );
        }

        return $content;
    }
}

/**
 * Initialize
 */
new PoP_Mentions();
