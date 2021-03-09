<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class GD_UserPlatform_DataLoad_FieldResolver_Users extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'shortDescription',
            'title',
            'displayEmail',
            'contact',
            'hasContact',
            'facebook',
            'twitter',
            'linkedin',
            'youtube',
            'instagram',
            'isProfile',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'shortDescription' => SchemaDefinition::TYPE_STRING,
            'title' => SchemaDefinition::TYPE_STRING,
            'displayEmail' => SchemaDefinition::TYPE_EMAIL,
            'contact' => SchemaDefinition::TYPE_STRING,
            'hasContact' => SchemaDefinition::TYPE_BOOL,
            'facebook' => SchemaDefinition::TYPE_URL,
            'twitter' => SchemaDefinition::TYPE_URL,
            'linkedin' => SchemaDefinition::TYPE_URL,
            'youtube' => SchemaDefinition::TYPE_URL,
            'instagram' => SchemaDefinition::TYPE_URL,
            'isProfile' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'isProfile',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'shortDescription' => $translationAPI->__('', ''),
            'title' => $translationAPI->__('', ''),
            'displayEmail' => $translationAPI->__('', ''),
            'contact' => $translationAPI->__('', ''),
            'hasContact' => $translationAPI->__('', ''),
            'facebook' => $translationAPI->__('', ''),
            'twitter' => $translationAPI->__('', ''),
            'linkedin' => $translationAPI->__('', ''),
            'youtube' => $translationAPI->__('', ''),
            'instagram' => $translationAPI->__('', ''),
            'isProfile' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $user = $resultItem;

        switch ($fieldName) {
            case 'shortDescription':
                return gdGetUserShortdescription($typeResolver->getID($user));

            case 'title':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_TITLE, true);

            case 'displayEmail':
                return (bool)\PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_DISPLAYEMAIL, true);

         // Override
            case 'contact':
                $value = array();
                // This one is a quasi copy/paste from the typeResolver
                if ($user_url = $typeResolver->resolveValue($user, 'websiteURL', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Website', 'pop-coreprocessors'),
                        'url' => maybeAddHttp($user_url),
                        'text' => $user_url,
                        'target' => '_blank',
                        'fontawesome' => 'fa-home',
                    );
                }
                if ($typeResolver->resolveValue($user, 'displayEmail', $variables, $expressions, $options)) {
                    if ($email = $typeResolver->resolveValue($user, 'email', $variables, $expressions, $options)) {
                        $value[] = array(
                            'fontawesome' => 'fa-envelope',
                            'tooltip' => TranslationAPIFacade::getInstance()->__('Email', 'pop-coreprocessors'),
                            'url' => 'mailto:'.$email,
                            'text' => $email
                        );
                    }
                }
                // if ($blog = $typeResolver->resolveValue($user, 'blog', $variables, $expressions, $options)) {

                //     $value[] = array(
                //         'tooltip' => TranslationAPIFacade::getInstance()->__('Blog', 'poptheme-wassup'),
                //         'url' => maybeAddHttp($blog),
                //         'text' => $blog,
                //         'target' => '_blank',
                //         'fontawesome' => 'fa-pencil',
                //     );
                // }
                if ($facebook = $typeResolver->resolveValue($user, 'facebook', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Facebook', 'poptheme-wassup'),
                        'fontawesome' => 'fa-facebook',
                        'url' => maybeAddHttp($facebook),
                        'text' => $facebook,
                        'target' => '_blank'
                    );
                }
                if ($twitter = $typeResolver->resolveValue($user, 'twitter', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Twitter', 'poptheme-wassup'),
                        'fontawesome' => 'fa-twitter',
                        'url' => maybeAddHttp($twitter),
                        'text' => $twitter,
                        'target' => '_blank'
                    );
                }
                if ($linkedin = $typeResolver->resolveValue($user, 'linkedin', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('LinkedIn', 'poptheme-wassup'),
                        'url' => maybeAddHttp($linkedin),
                        'text' => $linkedin,
                        'target' => '_blank',
                        'fontawesome' => 'fa-linkedin'
                    );
                }
                if ($youtube = $typeResolver->resolveValue($user, 'youtube', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Youtube', 'poptheme-wassup'),
                        'url' => maybeAddHttp($youtube),
                        'text' => $youtube,
                        'target' => '_blank',
                        'fontawesome' => 'fa-youtube',
                    );
                }
                if ($instagram = $typeResolver->resolveValue($user, 'instagram', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Instagram', 'poptheme-wassup'),
                        'url' => maybeAddHttp($instagram),
                        'text' => $instagram,
                        'target' => '_blank',
                        'fontawesome' => 'fa-instagram',
                    );
                }
                return $value;

            case 'hasContact':
                $contact = $typeResolver->resolveValue($resultItem, 'contact', $variables, $expressions, $options);
                return !empty($contact);

            case 'facebook':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_FACEBOOK, true);

            case 'twitter':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_TWITTER, true);

            case 'linkedin':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_LINKEDIN, true);

            case 'youtube':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_YOUTUBE, true);

            case 'instagram':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_INSTAGRAM, true);

            case 'isProfile':
                return isProfile($typeResolver->getID($user));
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_UserPlatform_DataLoad_FieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
