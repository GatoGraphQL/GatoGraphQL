<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_UserPlatform_DataLoad_ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'shortDescription' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'title' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'displayEmail' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver::class,
            'contact' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasContact' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'facebook' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'twitter' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'linkedin' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'youtube' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'instagram' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'isProfile' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'isProfile' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
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
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        $user = $object;

        switch ($fieldName) {
            case 'shortDescription':
                return gdGetUserShortdescription($objectTypeResolver->getID($user));

            case 'title':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_TITLE, true);

            case 'displayEmail':
                return (bool)\PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_DISPLAYEMAIL, true);

         // Override
            case 'contact':
                $value = array();
                // This one is a quasi copy/paste from the typeResolver
                if ($user_url = $objectTypeResolver->resolveValue($user, 'websiteURL', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Website', 'pop-coreprocessors'),
                        'url' => maybeAddHttp($user_url),
                        'text' => $user_url,
                        'target' => '_blank',
                        'fontawesome' => 'fa-home',
                    );
                }
                if ($objectTypeResolver->resolveValue($user, 'displayEmail', $variables, $expressions, $options)) {
                    if ($email = $objectTypeResolver->resolveValue($user, 'email', $variables, $expressions, $options)) {
                        $value[] = array(
                            'fontawesome' => 'fa-envelope',
                            'tooltip' => TranslationAPIFacade::getInstance()->__('Email', 'pop-coreprocessors'),
                            'url' => 'mailto:'.$email,
                            'text' => $email
                        );
                    }
                }
                // if ($blog = $objectTypeResolver->resolveValue($user, 'blog', $variables, $expressions, $options)) {

                //     $value[] = array(
                //         'tooltip' => TranslationAPIFacade::getInstance()->__('Blog', 'poptheme-wassup'),
                //         'url' => maybeAddHttp($blog),
                //         'text' => $blog,
                //         'target' => '_blank',
                //         'fontawesome' => 'fa-pencil',
                //     );
                // }
                if ($facebook = $objectTypeResolver->resolveValue($user, 'facebook', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Facebook', 'poptheme-wassup'),
                        'fontawesome' => 'fa-facebook',
                        'url' => maybeAddHttp($facebook),
                        'text' => $facebook,
                        'target' => '_blank'
                    );
                }
                if ($twitter = $objectTypeResolver->resolveValue($user, 'twitter', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Twitter', 'poptheme-wassup'),
                        'fontawesome' => 'fa-twitter',
                        'url' => maybeAddHttp($twitter),
                        'text' => $twitter,
                        'target' => '_blank'
                    );
                }
                if ($linkedin = $objectTypeResolver->resolveValue($user, 'linkedin', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('LinkedIn', 'poptheme-wassup'),
                        'url' => maybeAddHttp($linkedin),
                        'text' => $linkedin,
                        'target' => '_blank',
                        'fontawesome' => 'fa-linkedin'
                    );
                }
                if ($youtube = $objectTypeResolver->resolveValue($user, 'youtube', $variables, $expressions, $options)) {
                    $value[] = array(
                        'tooltip' => TranslationAPIFacade::getInstance()->__('Youtube', 'poptheme-wassup'),
                        'url' => maybeAddHttp($youtube),
                        'text' => $youtube,
                        'target' => '_blank',
                        'fontawesome' => 'fa-youtube',
                    );
                }
                if ($instagram = $objectTypeResolver->resolveValue($user, 'instagram', $variables, $expressions, $options)) {
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
                $contact = $objectTypeResolver->resolveValue($object, 'contact', $variables, $expressions, $options);
                return !empty($contact);

            case 'facebook':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_FACEBOOK, true);

            case 'twitter':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_TWITTER, true);

            case 'linkedin':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_LINKEDIN, true);

            case 'youtube':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_YOUTUBE, true);

            case 'instagram':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_INSTAGRAM, true);

            case 'isProfile':
                return isProfile($objectTypeResolver->getID($user));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_UserPlatform_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
