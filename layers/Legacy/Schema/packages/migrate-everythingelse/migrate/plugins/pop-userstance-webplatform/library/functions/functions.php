<?php

\PoP\Root\App::addFilter('pop_componentVariationmanager:multilayout_labels', 'userstanceMultilayoutLabels');
function userstanceMultilayoutLabels($labels)
{
    $stance_names = PoP_UserStance_PostNameUtils::getTermNames();
    $label = '<span class="label label-%s">%s</span>';
    return array_merge(
        array(
            POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_PRO => sprintf(
                $label,
                'stances-pro',
                $stance_names[POP_USERSTANCE_TERM_STANCE_PRO]
            ),
            POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_NEUTRAL => sprintf(
                $label,
                'stances-neutral',
                $stance_names[POP_USERSTANCE_TERM_STANCE_NEUTRAL]
            ),
            POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_AGAINST => sprintf(
                $label,
                'stances-against',
                $stance_names[POP_USERSTANCE_TERM_STANCE_AGAINST]
            ),
        ),
        $labels
    );
}
