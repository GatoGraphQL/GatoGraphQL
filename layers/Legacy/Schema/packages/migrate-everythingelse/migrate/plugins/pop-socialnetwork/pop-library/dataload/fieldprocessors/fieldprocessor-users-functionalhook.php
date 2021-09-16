<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_FunctionalUsers extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'followUserURL',
            'unfollowUserURL',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'followUserURL' => SchemaDefinition::TYPE_URL,
            'unfollowUserURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'followUserURL' => $translationAPI->__('', ''),
            'unfollowUserURL' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $user = $object;
        switch ($fieldName) {
            case 'followUserURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Users\Constants\InputNames::USER_ID => $objectTypeResolver->getID($user),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_FOLLOWUSER));

            case 'unfollowUserURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Users\Constants\InputNames::USER_ID => $objectTypeResolver->getID($user),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_FunctionalUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
