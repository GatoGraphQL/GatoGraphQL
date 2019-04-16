<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_FIELDPROCESSOR_COMMENTS', 'comments');

class FieldProcessor_Comments extends \PoP\Engine\FieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_FIELDPROCESSOR_COMMENTS;
    }

    public function getValue($resultitem, $field)
    {

        // First Check if there's a hook to implement this field
        $hookValue = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_COMMENTS, $resultitem, $field);
        if (!\PoP\Engine\GeneralUtils::isError($hookValue)) {
            return $hookValue;
        }
    
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $comment = $resultitem;
        switch ($field) {
            case 'content':
                $value = gdCommentsApplyFilters($cmsresolver->getCommentContent($comment), true);
                break;

            case 'author-name':
                $value = $cmsapi->getUserDisplayName($cmsresolver->getCommentUserId($comment));
                break;

            case 'author-url':
                $value = $cmsapi->getAuthorPostsUrl($cmsresolver->getCommentUserId($comment));
                break;

            case 'author-email':
                $value = $cmsapi->getUserEmail($cmsresolver->getCommentUserId($comment));
                break;

            case 'author':
                $value = $cmsresolver->getCommentUserId($comment);
                break;

            case 'post':
            case 'post-id':
                $value = $cmsresolver->getCommentPostId($comment);
                break;

            case 'approved':
                $value = $cmsresolver->getCommentApproved($comment);
                break;

            case 'type':
                $value = $cmsresolver->getCommentType($comment);
                break;

            case 'parent':
                $value = $cmsresolver->getCommentParent($comment);
                break;

            case 'date':
                $value = mysql2date($cmsapi->getOption(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:option:dateFormat')), $cmsresolver->getCommentDateGmt($comment));
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
        $comment = $resultitem;
        return $cmsresolver->getCommentId($comment);
    }

    public function getFieldDefaultDataloader($field)
    {

        // First Check if there's a hook to implement this field
        $default_dataloader = $this->getHookFieldDefaultDataloader(GD_DATALOAD_FIELDPROCESSOR_COMMENTS, $field);
        if ($default_dataloader) {
            return $default_dataloader;
        }

        switch ($field) {
            case 'author':
                return GD_DATALOADER_CONVERTIBLEUSERLIST;

            case 'post':
            case 'post-id':
                return GD_DATALOADER_CONVERTIBLEPOSTLIST;
        
            case 'id':
            case 'parent':
                return GD_DATALOADER_COMMENTLIST;
        }

        return parent::getFieldDefaultDataloader($field);
    }
}

/**
 * Initialize
 */
new FieldProcessor_Comments();
