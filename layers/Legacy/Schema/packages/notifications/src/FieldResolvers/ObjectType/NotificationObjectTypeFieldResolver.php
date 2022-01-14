<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\IPScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class NotificationObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?IPScalarTypeResolver $ipScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    
    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setIPScalarTypeResolver(IPScalarTypeResolver $ipScalarTypeResolver): void
    {
        $this->ipScalarTypeResolver = $ipScalarTypeResolver;
    }
    final protected function getIPScalarTypeResolver(): IPScalarTypeResolver
    {
        return $this->ipScalarTypeResolver ??= $this->instanceManager->getInstance(IPScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
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
        return match($fieldName) {
            'user' => $this->getUserObjectTypeResolver(),
            'action' => $this->getStringScalarTypeResolver(),
            'objectType' => $this->getStringScalarTypeResolver(),
            'objectSubtype' => $this->getStringScalarTypeResolver(),
            'objectName' => $this->getStringScalarTypeResolver(),
            'objectID' => $this->getIdScalarTypeResolver(),
            'userID' => $this->getIdScalarTypeResolver(),
            'websiteURL' => $this->getUrlScalarTypeResolver(),
            'userCaps' => $this->getStringScalarTypeResolver(),
            'histIp' => $this->getIpScalarTypeResolver(),
            'histTime' => $this->getDateScalarTypeResolver(),
            'histTimeNogmt' => $this->getDateScalarTypeResolver(),
            'histTimeReadable' => $this->getStringScalarTypeResolver(),
            'status' => $this->getStringScalarTypeResolver(),
            'isStatusRead' => $this->getBooleanScalarTypeResolver(),
            'isStatusNotRead' => $this->getBooleanScalarTypeResolver(),
            'markAsReadURL' => $this->getUrlScalarTypeResolver(),
            'markAsUnreadURL' => $this->getUrlScalarTypeResolver(),
            'icon' => $this->getStringScalarTypeResolver(),
            'url' => $this->getUrlScalarTypeResolver(),
            'target' => $this->getStringScalarTypeResolver(),
            'message' => $this->getStringScalarTypeResolver(),
            'isPostNotification' => $this->getBooleanScalarTypeResolver(),
            'isUserNotification' => $this->getBooleanScalarTypeResolver(),
            'isCommentNotification' => $this->getBooleanScalarTypeResolver(),
            'isTaxonomyNotification' => $this->getBooleanScalarTypeResolver(),
            'isAction' => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
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
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'action' => $this->getTranslationAPI()->__('', ''),
            'objectType' => $this->getTranslationAPI()->__('', ''),
            'objectSubtype' => $this->getTranslationAPI()->__('', ''),
            'objectName' => $this->getTranslationAPI()->__('', ''),
            'objectID' => $this->getTranslationAPI()->__('', ''),
            'user' => $this->getTranslationAPI()->__('', ''),
            'userID' => $this->getTranslationAPI()->__('', ''),
            'websiteURL' => $this->getTranslationAPI()->__('', ''),
            'userCaps' => $this->getTranslationAPI()->__('', ''),
            'histIp' => $this->getTranslationAPI()->__('', ''),
            'histTime' => $this->getTranslationAPI()->__('', ''),
            'histTimeNogmt' => $this->getTranslationAPI()->__('', ''),
            'histTimeReadable' => $this->getTranslationAPI()->__('', ''),
            'status' => $this->getTranslationAPI()->__('', ''),
            'isStatusRead' => $this->getTranslationAPI()->__('', ''),
            'isStatusNotRead' => $this->getTranslationAPI()->__('', ''),
            'markAsReadURL' => $this->getTranslationAPI()->__('', ''),
            'markAsUnreadURL' => $this->getTranslationAPI()->__('', ''),
            'icon' => $this->getTranslationAPI()->__('', ''),
            'url' => $this->getTranslationAPI()->__('', ''),
            'target' => $this->getTranslationAPI()->__('', ''),
            'message' => $this->getTranslationAPI()->__('', ''),
            'isPostNotification' => $this->getTranslationAPI()->__('', ''),
            'isUserNotification' => $this->getTranslationAPI()->__('', ''),
            'isCommentNotification' => $this->getTranslationAPI()->__('', ''),
            'isTaxonomyNotification' => $this->getTranslationAPI()->__('', ''),
            'isAction' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'isAction' => [
                'action' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isAction' => 'action'] => $this->getTranslationAPI()->__('The action to check against the notification', 'pop-posts'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isAction' => 'action'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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
                return $notification->hist_time - ($this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:gmtOffset')) * 3600);
            case 'histTimeReadable':
                // Must convert date using GMT
                return sprintf(
                    $this->getTranslationAPI()->__('%s ago', 'pop-notifications'),
                    \humanTiming($notification->hist_time - ($this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:gmtOffset')) * 3600))
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
                        $comment = $this->getCommentTypeAPI()->getComment($notification->object_id);
                        return $customPostTypeAPI->getPermalink($this->getCommentTypeAPI()->getCommentPostId($comment));
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
