<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Thumbs for the Links depending on their domain
 */

HooksAPIFacade::getInstance()->addFilter('PoP_MediaHostThumbs_Utils:nonembeddable-hosts', 'wassupNonembeddablehosts');
function wassupNonembeddablehosts($nonembeddable)
{
    return array_merge(
        $nonembeddable,
        array(
            'www.dropbox.com',
            'www.academia.edu',
            'www.slideshare.net',
            'www.facebook.com',
            'www.fb.com',
            'www.theguardian.com',
            'www.eff.org',
            'www.reddit.com',
            'reddit.com',
            'theconversation.com',
            'www.google.com',
        )
    );
}
