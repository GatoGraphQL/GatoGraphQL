<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostLinkMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class MutationResolverUtils
{
    /**
     * @param string[] $errors
     */
    public static function validateContent(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        if (empty($fieldDataAccessor->getValue('content'))) {
            // The link will be the content. So then replace the error message if the content (link) is empty
            // Add the error message at the beginning, since the Link input is shown before the Title input
            array_splice($errors, array_search(TranslationAPIFacade::getInstance()->__('The content cannot be empty', 'poptheme-wassup'), $errors), 1);
            array_unshift($errors, TranslationAPIFacade::getInstance()->__('The link cannot be empty', 'poptheme-wassup'));
        } else {
            // the content is actually the external URL, so validate it has a right format
            if (!isValidUrl($fieldDataAccessor->getValue('content'))) {
                array_unshift($errors, TranslationAPIFacade::getInstance()->__('Invalid Link URL', 'poptheme-wassup'));
            }
        }
    }
}
