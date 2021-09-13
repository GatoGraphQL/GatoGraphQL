<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\Users\TypeResolvers\ObjectType\UserTypeResolver;
use WP_User;

class UserFieldResolver extends AbstractQueryableFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected DateFormatterInterface $dateFormatter
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'nicename',
            'nickname',
            'locale',
            'registeredDate',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'nicename' => SchemaDefinition::TYPE_STRING,
            'nickname' => SchemaDefinition::TYPE_STRING,
            'locale' => SchemaDefinition::TYPE_STRING,
            'registeredDate' => SchemaDefinition::TYPE_STRING,
            default => parent::getSchemaFieldType($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'nicename',
            'nickname',
            'locale',
            'registeredDate'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'nicename' => $this->translationAPI->__('User\'s nicename', 'pop-users'),
            'nickname' => $this->translationAPI->__('User\'s nickname', 'pop-users'),
            'locale' => $this->translationAPI->__('Retrieves the locale of a user', 'pop-users'),
            'registeredDate' => $this->translationAPI->__('The date the user registerd on the site', 'pop-users'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'registeredDate' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
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
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        /** @var WP_User */
        $user = $resultItem;
        switch ($fieldName) {
            case 'nicename':
                return $user->user_nicename;
            case 'nickname':
                return $user->nickname;
            case 'locale':
                return \get_user_locale($user);
            case 'registeredDate':
                return $this->dateFormatter->format(
                    $fieldArgs['format'],
                    $user->user_registered
                );
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
