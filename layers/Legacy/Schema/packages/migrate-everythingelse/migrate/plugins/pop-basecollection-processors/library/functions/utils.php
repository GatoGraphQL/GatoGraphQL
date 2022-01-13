<?php

class PoP_BaseCollectionProcessors_Utils
{
    public static function useSkeletonscreenForExternalDomain()
    {

        // Comment Leo 24/05/2018: initially disabled, because there is some bug for the Events Calendar in GetPoP homepage:
        // it draws the event icon in the calendar without a title/url for the skeleton-screen and, when the request comes back,
        // it keeps it like that
        return \PoP\Root\App::getHookManager()->applyFilters('useSkeletonscreenForExternalDomain', false);
    }
}
