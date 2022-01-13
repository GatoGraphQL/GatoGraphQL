<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/*
 * Builds the array with key => value for the industries
 */
function gdBuildSelectOptions($options, $label = null)
{
    $return = array();
    
    // First option: Please Select
    if ($label) {
        $return[""] = TranslationAPIFacade::getInstance()->__(sprintf('Select %s', $label), 'pop-coreprocessors');
    }
    
    if ($options) {
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        foreach ($options as $option) {
            $return[$cmsapplicationhelpers->sanitizeTitle($option)] = TranslationAPIFacade::getInstance()->__($option, 'pop-coreprocessors');
        }
    }
    
    return $return;
}

function getHtmlAttribute($html, $tag, $att)
{
    
    // With images from FB, this produces an exception:
    // Warning: DOMDocument::loadHTML(): htmlParseEntityRef: expecting ';' in Entity,
    // explanation in http://stackoverflow.com/questions/1685277/warning-domdocumentloadhtml-htmlparseentityref-expecting-in-entity

    if (!$html) {
        return null;
    }
    
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    foreach ($dom->getElementsByTagName($tag) as $node) {
        return $node->getAttribute($att);
    }
}

function arrayToQuotedString($array, $quote = '"')
{
    return $quote.implode($quote.', '.$quote, $array).$quote;
}
