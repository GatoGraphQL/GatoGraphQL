<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class PoP_Avatar_DataLoad_FieldResolver_Users extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'userphoto',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
			'userphoto' => SchemaDefinition::TYPE_OBJECT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'userphoto' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $user = $resultItem;
        switch ($fieldName) {
            case 'userphoto':
                $userphoto = gdGetAvatarPhotoinfo($typeResolver->getID($user));
                return array(
                    'src' => $userphoto['src'],
                    'width' => $userphoto['width'],
                    'height' => $userphoto['height']
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_Avatar_DataLoad_FieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
