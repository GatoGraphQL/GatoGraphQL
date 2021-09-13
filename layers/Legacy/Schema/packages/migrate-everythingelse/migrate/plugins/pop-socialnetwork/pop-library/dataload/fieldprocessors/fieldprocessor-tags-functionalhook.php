<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagTypeResolver;

class GD_DataLoad_ObjectTypeFieldResolver_Tags extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'subscribeToTagURL',
            'unsubscribeFromTagURL',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'subscribeToTagURL' => SchemaDefinition::TYPE_URL,
            'unsubscribeFromTagURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'subscribeToTagURL' => $translationAPI->__('', ''),
            'unsubscribeFromTagURL' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $tag = $resultItem;
        switch ($fieldName) {
            case 'subscribeToTagURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Tags\Constants\InputNames::TAG_ID => $relationalTypeResolver->getID($tag),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG));

            case 'unsubscribeFromTagURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Tags\Constants\InputNames::TAG_ID => $relationalTypeResolver->getID($tag),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG));
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_DataLoad_ObjectTypeFieldResolver_Tags())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
