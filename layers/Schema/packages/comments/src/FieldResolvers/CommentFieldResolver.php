<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class CommentFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(CommentTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'content',
            'authorName',
            'authorURL',
            'authorEmail',
            'customPost',
            'customPostID',
            'approved',
            'type',
            'parent',
            'date',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'content' => SchemaDefinition::TYPE_STRING,
            'authorName' => SchemaDefinition::TYPE_STRING,
            'authorURL' => SchemaDefinition::TYPE_URL,
            'authorEmail' => SchemaDefinition::TYPE_EMAIL,
            'customPost' => SchemaDefinition::TYPE_ID,
            'customPostID' => SchemaDefinition::TYPE_ID,
            'approved' => SchemaDefinition::TYPE_BOOL,
            'type' => SchemaDefinition::TYPE_STRING,
            'parent' => SchemaDefinition::TYPE_ID,
            'date' => SchemaDefinition::TYPE_DATE,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'content':
            case 'customPost':
            case 'customPostID':
            case 'approved':
            case 'type':
            case 'date':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'content' => $translationAPI->__('Comment\'s content', 'pop-comments'),
            'authorName' => $translationAPI->__('Comment author\'s name', 'pop-comments'),
            'authorURL' => $translationAPI->__('Comment author\'s URL', 'pop-comments'),
            'authorEmail' => $translationAPI->__('Comment author\'s email', 'pop-comments'),
            'customPost' => $translationAPI->__('Custom post to which the comment was added', 'pop-comments'),
            'customPostID' => $translationAPI->__('ID of the custom post to which the comment was added', 'pop-comments'),
            'approved' => $translationAPI->__('Is the comment approved?', 'pop-comments'),
            'type' => $translationAPI->__('Type of comment', 'pop-comments'),
            'parent' => $translationAPI->__('Parent comment (if this comment is a response to another one)', 'pop-comments'),
            'date' => $translationAPI->__('Date when the comment was added', 'pop-comments'),
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
        $cmscommentsresolver = \PoPSchema\Comments\ObjectPropertyResolverFactory::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $comment = $resultItem;
        switch ($fieldName) {
            case 'content':
                return $cmscommentsresolver->getCommentContent($comment);

            case 'authorName':
                return $cmsusersapi->getUserDisplayName($cmscommentsresolver->getCommentUserId($comment));

            case 'authorURL':
                return $cmsusersapi->getUserURL($cmscommentsresolver->getCommentUserId($comment));

            case 'authorEmail':
                return $cmsusersapi->getUserEmail($cmscommentsresolver->getCommentUserId($comment));

            case 'customPost':
            case 'customPostID':
                return $cmscommentsresolver->getCommentPostId($comment);

            case 'approved':
                return $cmscommentsresolver->isCommentApproved($comment);

            case 'type':
                return $cmscommentsresolver->getCommentType($comment);

            case 'parent':
                return $cmscommentsresolver->getCommentParent($comment);

            case 'date':
                return $cmsengineapi->getDate($fieldArgs['format'], $cmscommentsresolver->getCommentDateGmt($comment));
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        $cmsService = CMSServiceFacade::getInstance();
        switch ($fieldName) {
            case 'date':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $translationAPI->__('Date format, as defined in %s', 'pop-comments'),
                                'https://www.php.net/manual/en/function.date.php'
                            ),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'customPost':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);

            case 'parent':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
