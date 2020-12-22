<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_OrderPost extends \PoP\Engine\GD_FormInput_Order
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        
        $values = array_merge(
            $values,
            array(
                'date|DESC' => TranslationAPIFacade::getInstance()->__('Latest published', 'pop-application'),
                'date|ASC' => TranslationAPIFacade::getInstance()->__('Earliest published', 'pop-application'),
                'comment_count|DESC' => TranslationAPIFacade::getInstance()->__('Most comments', 'pop-application'),
                'comment_count|ASC' => TranslationAPIFacade::getInstance()->__('Less comments', 'pop-application'),
                'title|ASC' => TranslationAPIFacade::getInstance()->__('Title ascending', 'pop-application'),
                'title|DESC' => TranslationAPIFacade::getInstance()->__('Title descending', 'pop-application')
            )
        );
        
        return $values;
    }
}
