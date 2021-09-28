<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\TypeResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Notifications\RelationalTypeDataLoaders\ObjectType\NotificationTypeDataLoader;

class NotificationObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected NotificationTypeDataLoader $notificationTypeDataLoader;
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        SchemaNamespacingServiceInterface $schemaNamespacingService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        FeedbackMessageStoreInterface $feedbackMessageStore,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ErrorProviderInterface $errorProvider,
        NotificationTypeDataLoader $notificationTypeDataLoader,
    ) {
        $this->notificationTypeDataLoader = $notificationTypeDataLoader;
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $schemaNamespacingService,
            $schemaDefinitionService,
            $feedbackMessageStore,
            $fieldQueryInterpreter,
            $errorProvider,
        );
    }
    
    public function getTypeName(): string
    {
        return 'Notification';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Notifications for the user', 'notifications');
    }

    public function getID(object $object): string | int | null
    {
        $notification = $object;
        return $notification->histid;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->notificationTypeDataLoader;
    }
}
