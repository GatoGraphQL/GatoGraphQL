<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers;

use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\AbstractObjectTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\TypeDataLoaders\UserTypeDataLoader;

class UserTypeResolver extends AbstractObjectTypeResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        SchemaNamespacingServiceInterface $schemaNamespacingService,
        FeedbackMessageStoreInterface $feedbackMessageStore,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ErrorProviderInterface $errorProvider,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        protected UserTypeAPIInterface $userTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $schemaNamespacingService,
            $feedbackMessageStore,
            $fieldQueryInterpreter,
            $errorProvider,
            $schemaDefinitionService,
        );
    }

    public function getTypeName(): string
    {
        return 'User';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a user', 'users');
    }

    public function getID(object $resultItem): string | int | null
    {
        $user = $resultItem;
        return $this->userTypeAPI->getUserId($user);
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return UserTypeDataLoader::class;
    }
}
