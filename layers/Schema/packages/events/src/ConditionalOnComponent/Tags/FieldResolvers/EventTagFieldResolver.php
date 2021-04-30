<?php

declare(strict_types=1);

namespace PoPSchema\Events\ConditionalOnComponent\Tags\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Events\FieldResolvers\AbstractEventFieldResolver;

// use PoPSchema\EventTags\TypeResolvers\EventTagTypeResolver;

/**
 * Fields for event tags
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 * @todo Create EventTagTypeResolver class, then remove abstract
 */
abstract class EventTagFieldResolver extends AbstractEventFieldResolver
{
    // public function getClassesToAttachTo(): array
    // {
    //     return array(EventTagTypeResolver::class);
    // }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'events' => $translationAPI->__('Events which contain this tag', 'events'),
            'eventCount' => $translationAPI->__('Number of events which contain this tag', 'events'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {

        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $tag = $resultItem;
        switch ($fieldName) {
            case 'events':
            case 'eventCount':
                $query['tag-ids'] = [$typeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
