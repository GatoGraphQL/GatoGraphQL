<?php
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\Object\AbstractCustomPostTypeResolver;
use PoPSchema\PostTags\TypeResolvers\Object\PostTagTypeResolver;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;

class GD_DataLoad_FunctionalFieldResolver extends AbstractFunctionalFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
            UserTypeResolver::class,
            PostTagTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'printURL',
            'embedURL',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'printURL' => SchemaDefinition::TYPE_URL,
            'embedURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'printURL' => $translationAPI->__('', ''),
            'embedURL' => $translationAPI->__('', ''),
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
        switch ($fieldName) {
            case 'printURL':
                $url = $relationalTypeResolver->resolveValue($resultItem, 'url', $variables, $expressions, $options);
                return PoP_Application_Engine_Utils::getPrintUrl($url);

            case 'embedURL':
                $url = $relationalTypeResolver->resolveValue($resultItem, 'url', $variables, $expressions, $options);
                return PoP_Application_Engine_Utils::getEmbedUrl($url);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
$translationAPI = TranslationAPIFacade::getInstance();
$hooksAPI = HooksAPIFacade::getInstance();
(new GD_DataLoad_FunctionalFieldResolver($translationAPI, $hooksAPI))->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
