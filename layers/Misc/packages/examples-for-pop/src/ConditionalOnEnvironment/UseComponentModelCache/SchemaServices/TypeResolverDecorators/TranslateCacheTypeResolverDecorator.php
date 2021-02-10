<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;

/**
 * Add directive @cache to fields expensive to calculate
 */
class TranslateCacheTypeResolverDecorator extends AbstractCacheTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return [
            AbstractTypeResolver::class,
        ];
    }

    /**
     * Get the directives to cache
     *
     * @return array
     */
    protected function getDirectiveNamesToCache(): array
    {
        /**
         * Currently disabled because it doesn't work when the <translate> directive is within a nested directive
         * This example fails:
         * https://newapi.getpop.org/api/graphql/?postId=1&query=post($postId)@post.content|date(d/m/Y)@date,getJSON(%22https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions%22)@userList|arrayUnique(extract(getSelfProp(%self%,%20userList),lang))@userLangs|extract(getSelfProp(%self%,%20userList),email)@userEmails|arrayFill(getJSON(sprintf(%22https://newapi.getpop.org/users/api/rest/?query=name|email%26emails[]=%s%22,[arrayJoin(getSelfProp(%self%,%20userEmails),%22%26emails[]=%22)])),getSelfProp(%self%,%20userList),email)@userData,self.post($postId)@post%3CcopyRelationalResults([content,%20date],[postContent,%20postDate])%3E|self.getSelfProp(%self%,%20postContent)@postContent%3Ctranslate(from:%20en,to:%20arrayDiff([getSelfProp(%self%,%20userLangs),[en]])),renameProperty(postContent-en)%3E|getSelfProp(%self%,%20userData)@userPostData%3CforEach%3CapplyFunction(function:%20arrayAddItem(array:%20[],value:%20%22%22),addArguments:%20[key:%20postContent,array:%20%value%,value:%20getSelfProp(%self%,sprintf(postContent-%s,[extract(%value%,%20lang)]))]),applyFunction(function:%20arrayAddItem(array:%20[],value:%20%22%22),addArguments:%20[key:%20header,array:%20%value%,value:%20sprintf(string:%20%22%3Cp%3EHi%20%s,%20we%20published%20this%20post%20on%20%s,%20enjoy!%3C/p%3E%22,values:%20[extract(%value%,%20name),getSelfProp(%self%,%20postDate)])])%3E%3E
         */
        $enabled = false;
        if ($enabled) {
            return [
                AbstractTranslateDirectiveResolver::getDirectiveName(),
            ];
        }
        return [];
    }
}
