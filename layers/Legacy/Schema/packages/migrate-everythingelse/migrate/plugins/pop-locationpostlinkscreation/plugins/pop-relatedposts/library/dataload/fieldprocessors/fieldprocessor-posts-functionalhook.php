<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;

class PoP_LocationPostLinksCreation_DataLoad_FieldResolver_FunctionalPosts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'addLocationPostLinkURL',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'addLocationPostLinkURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'addLocationPostLinkURL' => $translationAPI->__('', ''),
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
        $post = $resultItem;
        switch ($fieldName) {
            case 'addLocationPostLinkURL':
                $routes = array(
                    'addLocationPostLinkURL' => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
                );
                $route = $routes[$fieldName];

                $name = POP_INPUTNAME_REFERENCES.'[]';
                return GeneralUtils::addQueryArgs([
                    $name => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL($route));
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_LocationPostLinksCreation_DataLoad_FieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
