<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;

class GD_DataLoad_ObjectTypeFieldResolver_Tags extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'subscribeToTagURL',
            'unsubscribeFromTagURL',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'subscribeToTagURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'unsubscribeFromTagURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'subscribeToTagURL' => $translationAPI->__('', ''),
            'unsubscribeFromTagURL' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
        $tag = $object;
        switch ($fieldName) {
            case 'subscribeToTagURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Tags\Constants\InputNames::TAG_ID => $objectTypeResolver->getID($tag),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG));

            case 'unsubscribeFromTagURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Tags\Constants\InputNames::TAG_ID => $objectTypeResolver->getID($tag),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_DataLoad_ObjectTypeFieldResolver_Tags())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
