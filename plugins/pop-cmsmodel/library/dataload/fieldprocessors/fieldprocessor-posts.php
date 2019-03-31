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
        if (!\PoP\Engine\GeneralUtils::isError($hookValue)) {
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
                $value = $cmsapi->getPostStatus($this->getId($post)) == POP_POSTSTATUS_PUBLISHED;
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
                $value = $cmsapi->getPostCategories($this->getId($post), array(/*'fields' => 'ids'*/), ['return-type' => POP_RETURNTYPE_IDS]);
                if (!$value) {
                    $value = array();
                }
                break;

            case 'cat-slugs':
                $value = $cmsapi->getPostCategories($this->getId($post), array(/*'fields' => 'slugs'*/), ['return-type' => POP_RETURNTYPE_SLUGS]);
                break;

            case 'tags':
                $value = $cmsapi->getPostTags($this->getId($post), array(/*'fields' => 'ids'*/), ['return-type' => POP_RETURNTYPE_IDS]);
                break;

            case 'tag-names':
                $value = $cmsapi->getPostTags($this->getId($post), array(/*'fields' => 'names'*/), ['return-type' => POP_RETURNTYPE_NAMES]);
                break;

            case 'title':
                // $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('popcms:title', $cmsresolver->getPostTitle($post), $this->getId($post));
                $value = $cmsapi->getPostTitle($this->getId($post));
                break;

            case 'title-edit':
                if (current_user_can(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:capability:editPost'), $this->getId($post))) {
                    $value = $cmsresolver->getPostTitle($post);
                } else {
                    $value = '';
                }
                break;
            
            case 'content':
                $value = $cmsapi->getPostContent($this->getId($post));
                $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('pop_content', $value, $this->getId($post));
                break;

            case 'content-editor':
                if (current_user_can(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:capability:editPost'), $this->getId($post))) {
                    $value = $cmsapi->getPostEditorContent($this->getId($post));
                } else {
                    $value = '';
                }
                break;

            case 'content-edit':
                if (current_user_can(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:capability:editPost'), $this->getId($post))) {
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
                    'status' => POP_COMMENTSTATUS_APPROVED,
                    // 'type' => 'comment', // Only comments, no trackbacks or pingbacks
                    'post-id' => $this->getId($post),
                    // The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
                    'order' =>  'ASC',
                    'orderby' => \PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:dbcolumn:orderby:comments:date'),
                );
                $value = $cmsapi->getComments($query, ['return-type' => POP_RETURNTYPE_IDS]);
                // $comments = $cmsapi->getComments($query);
                // $value = array();
                // foreach ($comments as $comment) {
                //     $value[] = $cmsresolver->getCommentId($comment);
                // }
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
                $value = ($status == POP_POSTSTATUS_DRAFT);
                break;

            case 'date':
                $value = mysql2date($cmsapi->getOption(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:option:dateFormat')), $cmsresolver->getPostDate($post));
                break;

            case 'datetime':
                // If it is the current year, don't add the year. Otherwise, do
                // 15 Jul, 21:47 or // 15 Jul 2018, 21:47
                $date = $cmsresolver->getPostDate($post);
                $format = (mysql2date('Y', $date) == date('Y')) ? 'j M, H:i' : 'j M Y, H:i'; 
                $value = mysql2date($format, $date);
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
