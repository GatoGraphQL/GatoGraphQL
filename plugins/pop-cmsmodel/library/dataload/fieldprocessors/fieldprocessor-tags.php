<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_FIELDPROCESSOR_TAGS', 'tags');

class FieldProcessor_Tags extends \PoP\Engine\FieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_FIELDPROCESSOR_TAGS;
    }

    public function getValue($resultitem, $field)
    {

        // First Check if there's a hook to implement this field
        $hookValue = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_TAGS, $resultitem, $field);
        if (!is_wp_error($hookValue)) {
            return $hookValue;
        }
    
        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $tag = $resultitem;
        switch ($field) {
            case 'url':
                $value = $cmsapi->getTagLink($this->getId($tag));
                break;

            case 'endpoint':
                $value = \PoP\Engine\APIUtils::getEndpoint($this->getValue($resultitem, 'url'));
                break;

            case 'name':
                $value = $cmsresolver->getTagName($tag);
                break;

            case 'slug':
                $value = $cmsresolver->getTagSlug($tag);
                break;

            case 'term_group':
                $value = $cmsresolver->getTagTermGroup($tag);
                break;

            case 'term_taxonomy_id':
                $value = $cmsresolver->getTagTermTaxonomyId($tag);
                break;

            case 'taxonomy':
                $value = $cmsresolver->getTagTaxonomy($tag);
                break;

            case 'description':
                $value = $cmsresolver->getTagDescription($tag);
                break;

            case 'parent':
                $value = $cmsresolver->getTagParent($tag);
                break;

            case 'count':
                $value = $cmsresolver->getTagCount($tag);
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
        $tag = $resultitem;
        return $cmsresolver->getTagTermId($tag);
    }

    public function getFieldDefaultDataloader($field)
    {

        // First Check if there's a hook to implement this field
        $default_dataloader = $this->getHookFieldDefaultDataloader(GD_DATALOAD_FIELDPROCESSOR_TAGS, $field);
        if ($default_dataloader) {
            return $default_dataloader;
        }

        switch ($field) {
            case 'parent':
                return GD_DATALOADER_TAGLIST;
        }

        return parent::getFieldDefaultDataloader($field);
    }
}

/**
 * Initialize
 */
new FieldProcessor_Tags();
