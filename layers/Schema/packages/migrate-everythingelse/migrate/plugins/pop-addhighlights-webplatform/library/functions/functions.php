<?php

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:multilayout_labels', 'wassupMultilayoutLabels');
function wassupMultilayoutLabels($labels)
{
    $label = '<span class="label label-%s">%s</span>';
    return array_merge(
        array(
            POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT => sprintf(
                $label,
                'highlights',
                getRouteIcon(POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS, true).TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup')
            ),
        ),
        $labels
    );
}

HooksAPIFacade::getInstance()->addFilter('gd_postname', 'wassupPostname', 10, 2);
function wassupPostname($name, $post_id)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
        return TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup');
    }

    return $name;
}

HooksAPIFacade::getInstance()->addFilter('gd_posticon', 'wassupPosticon', 10, 2);
function wassupPosticon($icon, $post_id)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
        return getRouteIcon(\POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS, false);
    }

    return $icon;
}

// HooksAPIFacade::getInstance()->addFilter('gdPostParentpageid', 'wassupPostParentpageid', 10, 2);
// function wassupPostParentpageid($pageid, $post_id)
// {
//     $customostTypeAPI = CustomPostTypeAPIFacade::getInstance();
//     if ($customostTypeAPI->getCustomPostType($post_id) == POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
//         return POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS;
//     }

//     return $pageid;
// }
