<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_FIELDPROCESSOR_POSTS', 'posts');

class FieldProcessor_Posts extends \PoP\Engine\FieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_FIELDPROCESSOR_POSTS;
    }

    /**
     * Overridable function.
     * Needed for the Delegator, to cast an object from 'post' post_type to whichever corresponds ('attachment', 'event', etc)
     */
    // function cast($post) {

    //     return $post;
    // }
    
    public function getValue($resultitem, $field)
    {
    
        // First Check if there's a hook to implement this field
        $hookValue = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_POSTS, $resultitem, $field);
        if (!is_wp_error($hookValue)) {
            return $hookValue;
        }

        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $post = $resultitem;
        switch ($field) {
         // Mandatory: needed for the Multiple Layouts
            case 'post-type':
                $value = $cmsresolver->getPostType($post);
                break;

            case 'published':
                $value = $cmsapi->getPostStatus($this->getId($post)) == 'publish';
                break;

            case 'not-published':
                $value = !$this->getValue($post, 'published');
                break;

            case 'comments-url':
                return $this->getValue($post, 'url');

            case 'comments-count':
                $value = $cmsapi->getCommentsNumber($this->getId($post));
                break;

            case 'has-comments':
                $value = $this->getValue($post, 'comments-count') > 0;
                break;

            case 'published-with-comments':
                $value = $this->getValue($post, 'published') && $this->getValue($post, 'has-comments');
                break;

            case 'cat':
                // Simply return the first category
                if ($cats = $this->getValue($post, 'cats')) {
                    $value = $cats[0];
                }
                break;

            case 'cat-name':
                if ($cat = $this->getValue($post, 'cat')) {
                    $category = get_category($cat);
                    $value = $category->name;
                }
                break;

            case 'cats':
                $value = $cmsapi->wpGetPostCategories($this->getId($post), array('fields' => 'ids'));
                if (!$value) {
                    $value = array();
                }
                break;

            case 'cat-slugs':
                $value = $cmsapi->wpGetPostCategories($this->getId($post), array('fields' => 'slugs'));
                break;

            case 'tags':
                $value = $cmsapi->wpGetPostTags($this->getId($post), array('fields' => 'ids'));
                break;

            case 'tag-names':
                $value = $cmsapi->wpGetPostTags($this->getId($post), array('fields' => 'names'));
                break;

            case 'title':
                $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('the_title', $cmsresolver->getPostTitle($post), $this->getId($post));
                break;

            case 'title-edit':
                if (current_user_can('edit_post', $this->getId($post))) {
                    $value = $cmsresolver->getPostTitle($post);
                } else {
                    $value = '';
                }
                break;
            
            case 'content':
                $value = $cmsresolver->getPostContent($post);
                $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('pop_content_pre', $value, $this->getId($post));
                $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('the_content', $value);
                $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('pop_content', $value, $this->getId($post));
                break;

            case 'content-editor':
                if (current_user_can('edit_post', $this->getId($post))) {
                    $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('the_editor_content', $cmsresolver->getPostContent($post));
                } else {
                    $value = '';
                }
                break;

            case 'content-edit':
                if (current_user_can('edit_post', $this->getId($post))) {
                    $value = $cmsresolver->getPostContent($post);
                } else {
                    $value = '';
                }
                break;
        
            case 'url':
                $value = $cmsapi->getPermalink($this->getId($post));
                break;

            case 'endpoint':
                $value = \PoP\Engine\APIUtils::getEndpoint($this->getValue($resultitem, 'url'));
                break;

            case 'excerpt':
                $value = $cmsapi->getTheExcerpt($this->getId($post));
                break;

            case 'comments':
                $query = array(
                    'status' => 'approve',
                    'type' => 'comment', // Only comments, no trackbacks or pingbacks
                    'post_id' => $this->getId($post),
                    // The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
                    'order' =>  'ASC',
                    'orderby' => 'comment_date_gmt',
                );
                $comments = $cmsapi->getComments($query);
                $value = array();
                foreach ($comments as $comment) {
                    $value[] = $cmsresolver->getCommentId($comment);
                }
                break;

            case 'has-thumb':
                $value = $cmsapi->hasPostThumbnail($this->getId($post));
                break;

            case 'featuredimage':
                $value = $cmsapi->getPostThumbnailId($this->getId($post));
                break;
    
            case 'author':
                $value = $cmsresolver->getPostAuthor($post);
                break;

            case 'status':
                $value = $cmsapi->getPostStatus($this->getId($post));
                break;

            case 'is-draft':
                $status = $cmsapi->getPostStatus($this->getId($post));
                $value = ($status == 'draft');
                break;

            case 'date':
                $value = mysql2date($cmsapi->getOption('date_format'), $cmsresolver->getPostDate($post));
                break;

            case 'datetime':
                // 15 Jul, 21:47
                $value = mysql2date('j M, H:i', $cmsresolver->getPostDate($post));
                break;

            case 'edit-url':
                $value = urldecode($cmsapi->getEditPostLink($this->getId($post)));
                break;

            case 'delete-url':
                $value = $cmsapi->getDeletePostLink($this->getId($post));
                break;
            
            default:
                $value = parent::getValue($resultitem, $field);
                break;
        }

        return $value;
    }

    public function getId($resultitem)
    {
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $post = $resultitem;
        return $cmsresolver->getPostId($post);
    }

    public function getFieldDefaultDataloader($field)
    {

        // First Check if there's a hook to implement this field
        $default_dataloader = $this->getHookFieldDefaultDataloader(GD_DATALOAD_FIELDPROCESSOR_POSTS, $field);
        if ($default_dataloader) {
            return $default_dataloader;
        }

        switch ($field) {
            case 'tags':
                return GD_DATALOADER_TAGLIST;

            case 'comments':
                return GD_DATALOADER_COMMENTLIST;

            case 'author':
                return GD_DATALOADER_CONVERTIBLEUSERLIST;
        }

        return parent::getFieldDefaultDataloader($field);
    }
}

/**
 * Initialize
 */
new FieldProcessor_Posts();
