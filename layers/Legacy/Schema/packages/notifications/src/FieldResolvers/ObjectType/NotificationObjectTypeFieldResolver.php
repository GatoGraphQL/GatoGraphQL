<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\IPScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class NotificationObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected CommentTypeAPIInterface $commentTypeAPI;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected DateScalarTypeResolver $dateScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected IPScalarTypeResolver $ipScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected URLScalarTypeResolver $urlScalarTypeResolver;
    protected UserObjectTypeResolver $userObjectTypeResolver;
    public function __construct(
        CommentTypeAPIInterface $commentTypeAPI,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        DateScalarTypeResolver $dateScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        IPScalarTypeResolver $ipScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        URLScalarTypeResolver $urlScalarTypeResolver,
        UserObjectTypeResolver $userObjectTypeResolver,
    ) {
        $this->commentTypeAPI = $commentTypeAPI;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->ipScalarTypeResolver = $ipScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            NotificationObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'action',
            'objectType',
            'objectSubtype',
            'objectName',
            'objectID',
            'user',
            'userID',
            'websiteURL',
            'userCaps',
            'histIp',
            'histTime',
            'histTimeNogmt',
            'histTimeReadable',
            'status',
            'isStatusRead',
            'isStatusNotRead',
            'markAsReadURL',
            'markAsUnreadURL',
            'icon',
            'url',
            'target',
            'message',
            'isPostNotification',
            'isUserNotification',
            'isCommentNotification',
            'isTaxonomyNotification',
            'isAction',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'user' => $this->userObjectTypeResolver,
            'action' => $this->stringScalarTypeResolver,
            'objectType' => $this->stringScalarTypeResolver,
            'objectSubtype' => $this->stringScalarTypeResolver,
            'objectName' => $this->stringScalarTypeResolver,
            'objectID' => $this->idScalarTypeResolver,
            'userID' => $this->idScalarTypeResolver,
            'websiteURL' => $this->urlScalarTypeResolver,
            'userCaps' => $this->stringScalarTypeResolver,
            'histIp' => $this->ipScalarTypeResolver,
            'histTime' => $this->dateScalarTypeResolver,
            'histTimeNogmt' => $this->dateScalarTypeResolver,
            'histTimeReadable' => $this->stringScalarTypeResolver,
            'status' => $this->stringScalarTypeResolver,
            'isStatusRead' => $this->booleanScalarTypeResolver,
            'isStatusNotRead' => $this->booleanScalarTypeResolver,
            'markAsReadURL' => $this->urlScalarTypeResolver,
            'markAsUnreadURL' => $this->urlScalarTypeResolver,
            'icon' => $this->stringScalarTypeResolver,
            'url' => $this->urlScalarTypeResolver,
            'target' => $this->stringScalarTypeResolver,
            'message' => $this->stringScalarTypeResolver,
            'isPostNotification' => $this->booleanScalarTypeResolver,
            'isUserNotification' => $this->booleanScalarTypeResolver,
            'isCommentNotification' => $this->booleanScalarTypeResolver,
            'isTaxonomyNotification' => $this->booleanScalarTypeResolver,
            'isAction' => $this->booleanScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'action',
            'objectType',
            'objectID',
            'user',
            'userID',
            'histTime',
            'histTimeNogmt',
            'histTimeReadable',
            'status',
            'isStatusRead',
            'isStatusNotRead',
            'isPostNotification',
            'isUserNotification',
            'isCommentNotification',
            'isTaxonomyNotification',
            'isAction'
                => SchemaTypeModifiers::NON_NULLABLE,
            'userCaps'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'action' => $this->translationAPI->__('', ''),
            'objectType' => $this->translationAPI->__('', ''),
            'objectSubtype' => $this->translationAPI->__('', ''),
            'objectName' => $this->translationAPI->__('', ''),
            'objectID' => $this->translationAPI->__('', ''),
            'user' => $this->translationAPI->__('', ''),
            'userID' => $this->translationAPI->__('', ''),
            'websiteURL' => $this->translationAPI->__('', ''),
            'userCaps' => $this->translationAPI->__('', ''),
            'histIp' => $this->translationAPI->__('', ''),
            'histTime' => $this->translationAPI->__('', ''),
            'histTimeNogmt' => $this->translationAPI->__('', ''),
            'histTimeReadable' => $this->translationAPI->__('', ''),
            'status' => $this->translationAPI->__('', ''),
            'isStatusRead' => $this->translationAPI->__('', ''),
            'isStatusNotRead' => $this->translationAPI->__('', ''),
            'markAsReadURL' => $this->translationAPI->__('', ''),
            'markAsUnreadURL' => $this->translationAPI->__('', ''),
            'icon' => $this->translationAPI->__('', ''),
            'url' => $this->translationAPI->__('', ''),
            'target' => $this->translationAPI->__('', ''),
            'message' => $this->translationAPI->__('', ''),
            'isPostNotification' => $this->translationAPI->__('', ''),
            'isUserNotification' => $this->translationAPI->__('', ''),
            'isCommentNotification' => $this->translationAPI->__('', ''),
            'isTaxonomyNotification' => $this->translationAPI->__('', ''),
            'isAction' => $this->translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'isAction':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'action',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The action to check against the notification', 'pop-posts'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
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
        $notification = $object;
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $taxonomyapi = TaxonomyTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'action':
                return $notification->action;
            case 'objectType':
                return $notification->object_type;
            case 'objectSubtype':
                return $notification->object_subtype;
            case 'objectName':
                return $notification->object_name;
            case 'objectID':
                return $notification->object_id;
            case 'user':
            case 'userID':
                return $notification->user_id;
            case 'websiteURL':
                return $userTypeAPI->getUserURL($notification->user_id);
            case 'userCaps':
                return $notification->user_caps;
            case 'histIp':
                return $notification->hist_ip;
            case 'histTime':
                return $notification->hist_time;
            case 'histTimeNogmt':
                // In the DB, the time is saved without GMT. However, in the front-end we need the GMT factored in,
                // because moment.js will
                return $notification->hist_time - ($this->cmsService->getOption($this->nameResolver->getName('popcms:option:gmtOffset')) * 3600);
            case 'histTimeReadable':
                // Must convert date using GMT
                return sprintf(
                    $this->translationAPI->__('%s ago', 'pop-notifications'),
                    \humanTiming($notification->hist_time - ($this->cmsService->getOption($this->nameResolver->getName('popcms:option:gmtOffset')) * 3600))
                );

            case 'status':
                $value = $notification->status;
                if (!$value) {
                    // Make sure to return an empty string back, since this is used as a class
                    return '';
                }
                return $value;

            case 'isStatusRead':
                $status = $objectTypeResolver->resolveValue($object, 'status', $variables, $expressions, $options);
                return ($status == AAL_POP_STATUS_READ);

            case 'isStatusNotRead':
                $is_read = $objectTypeResolver->resolveValue($object, 'isStatusRead', $variables, $expressions, $options);
                return !$is_read;

            case 'markAsReadURL':
                return GeneralUtils::addQueryArgs([
                    'nid' => $objectTypeResolver->getID($notification),
                ], RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD));

            case 'markAsUnreadURL':
                return GeneralUtils::addQueryArgs([
                    'nid' => $objectTypeResolver->getID($notification),
                ], RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD));

            case 'icon':
                // URL depends basically on the action performed on the object type
                switch ($notification->object_type) {
                    case 'Post':
                        return \gdGetPosticon($notification->object_id);
                }
                return null;

            case 'url':
                // URL depends basically on the action performed on the object type
                switch ($notification->object_type) {
                    case 'Post':
                        return $customPostTypeAPI->getPermalink($notification->object_id);

                    case 'User':
                        return $userTypeAPI->getUserURL($notification->object_id);

                    case 'Taxonomy':
                        return $taxonomyapi->getTermLink($notification->object_id);

                    case 'Comments':
                        $comment = $this->commentTypeAPI->getComment($notification->object_id);
                        return $customPostTypeAPI->getPermalink($this->commentTypeAPI->getCommentPostId($comment));
                }
                return null;

            case 'target':
                // By default, no need to specify the target. This can be overriden
                return null;

            case 'message':
                return $notification->object_name;

            case 'isPostNotification':
                return $notification->object_type == 'Post';

            case 'isUserNotification':
                return $notification->object_type == 'User';

            case 'isCommentNotification':
                return $notification->object_type == 'Comments';

            case 'isTaxonomyNotification':
                return $notification->object_type == 'Taxonomy';

            case 'isAction':
                return $fieldArgs['action'] == $notification->action;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
