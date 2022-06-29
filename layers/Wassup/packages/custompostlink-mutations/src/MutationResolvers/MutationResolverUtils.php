<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostLinkMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class MutationResolverUtils
{
    public static function validateContent(array &$errors, WithArgumentsInterface $withArgumentsAST): void
    {
        if (empty($withArgumentsAST->getArgumentValue('content'))) {
            // The link will be the content. So then replace the error message if the content (link) is empty
            // Add the error message at the beginning, since the Link input is shown before the Title input
            array_splice($errors, array_search(TranslationAPIFacade::getInstance()->__('The content cannot be empty', 'poptheme-wassup'), $errors), 1);
            array_unshift($errors, TranslationAPIFacade::getInstance()->__('The link cannot be empty', 'poptheme-wassup'));
        } else {
            // the content is actually the external URL, so validate it has a right format
            if (!isValidUrl($withArgumentsAST->getArgumentValue('content'))) {
                array_unshift($errors, TranslationAPIFacade::getInstance()->__('Invalid Link URL', 'poptheme-wassup'));
            }
        }
    }
}
