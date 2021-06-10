<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Notifications\TypeResolvers\NotificationTypeResolver;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class NotificationFieldResolver extends AbstractDBDataFieldResolver
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        protected CommentTypeAPIInterface $commentTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
        );
    }

    public function getClassesToAttachTo(): array
    {
        return array(NotificationTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'action',
            'objectType',
            'objectSubtype',
            'objectName',
            'objectID',
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'action' => SchemaDefinition::TYPE_STRING,
            'objectType' => SchemaDefinition::TYPE_STRING,
            'objectSubtype' => SchemaDefinition::TYPE_STRING,
            'objectName' => SchemaDefinition::TYPE_STRING,
            'objectID' => SchemaDefinition::TYPE_ID,
            'userID' => SchemaDefinition::TYPE_ID,
            'websiteURL' => SchemaDefinition::TYPE_URL,
            'userCaps' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'histIp' => SchemaDefinition::TYPE_IP,
            'histTime' => SchemaDefinition::TYPE_DATE,
            'histTimeNogmt' => SchemaDefinition::TYPE_DATE,
            'histTimeReadable' => SchemaDefinition::TYPE_STRING,
            'status' => SchemaDefinition::TYPE_STRING,
            'isStatusRead' => SchemaDefinition::TYPE_BOOL,
            'isStatusNotRead' => SchemaDefinition::TYPE_BOOL,
            'markAsReadURL' => SchemaDefinition::TYPE_URL,
            'markAsUnreadURL' => SchemaDefinition::TYPE_URL,
            'icon' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'target' => SchemaDefinition::TYPE_STRING,
            'message' => SchemaDefinition::TYPE_STRING,
            'isPostNotification' => SchemaDefinition::TYPE_BOOL,
            'isUserNotification' => SchemaDefinition::TYPE_BOOL,
            'isCommentNotification' => SchemaDefinition::TYPE_BOOL,
            'isTaxonomyNotification' => SchemaDefinition::TYPE_BOOL,
            'isAction' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'action',
            'objectType',
            'objectID',
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
            'isAction',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'action' => $this->translationAPI->__('', ''),
            'objectType' => $this->translationAPI->__('', ''),
            'objectSubtype' => $this->translationAPI->__('', ''),
            'objectName' => $this->translationAPI->__('', ''),
            'objectID' => $this->translationAPI->__('', ''),
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $notification = $resultItem;
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
                $status = $typeResolver->resolveValue($resultItem, 'status', $variables, $expressions, $options);
                return ($status == AAL_POP_STATUS_READ);

            case 'isStatusNotRead':
                $is_read = $typeResolver->resolveValue($resultItem, 'isStatusRead', $variables, $expressions, $options);
                return !$is_read;

            case 'markAsReadURL':
                return GeneralUtils::addQueryArgs([
                    'nid' => $typeResolver->getID($notification),
                ], RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD));

            case 'markAsUnreadURL':
                return GeneralUtils::addQueryArgs([
                    'nid' => $typeResolver->getID($notification),
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'userID':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
