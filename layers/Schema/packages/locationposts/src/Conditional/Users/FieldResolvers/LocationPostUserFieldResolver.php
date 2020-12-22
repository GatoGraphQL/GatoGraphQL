<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\Conditional\Users\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\LocationPosts\FieldResolvers\AbstractLocationPostFieldResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class LocationPostUserFieldResolver extends AbstractLocationPostFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'locationposts' => $translationAPI->__('Location Posts by the user', 'locationposts'),
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

        $user = $resultItem;
        switch ($fieldName) {
            case 'locationposts':
                $query['authors'] = [$typeResolver->getID($user)];
                break;
        }

        return $query;
    }
}
