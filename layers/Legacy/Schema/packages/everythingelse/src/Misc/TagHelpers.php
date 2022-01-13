<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Misc;

use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class TagHelpers
{
    public static function showTagSymbol()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_TagUtils:showTagSymbol', true);
    }

    public static function getTagSymbol()
    {
        return self::showTagSymbol() ? HooksAPIFacade::getInstance()->applyFilters('PoP_TagUtils:tag_symbol', '#') : '';
    }

    public static function getTagSymbolNameDescription($tag)
    {
        $cmstaxonomiesresolver = PostTagTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
        $value = $applicationtaxonomyapi->getTagSymbolName($tag);

        // If there's a description, then use it
        if ($description = $cmstaxonomiesresolver->getTagDescription($tag)) {
            $value = sprintf(
                TranslationAPIFacade::getInstance()->__('%1$s (%2$s)', 'pop-everythingelse'),
                $value,
                $description
            );
        }

        return $value;
    }

    public static function getTagNameDescription($tag)
    {
        $cmstaxonomiesresolver = PostTagTypeAPIFacade::getInstance();
        $value = $cmstaxonomiesresolver->getTagName($tag);

        // If there's a description, then use it
        if ($description = $cmstaxonomiesresolver->getTagDescription($tag)) {
            $value = sprintf(
                TranslationAPIFacade::getInstance()->__('%1$s (%2$s)', 'pop-application'),
                $value,
                $description
            );
        }

        return $value;
    }
}
