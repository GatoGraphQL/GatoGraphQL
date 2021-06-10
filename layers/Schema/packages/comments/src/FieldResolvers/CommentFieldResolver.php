<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\Constants\Status;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CommentFieldResolver extends AbstractQueryableFieldResolver
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
            'responses',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
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
            'responses' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
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
            case 'responses':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'content' => $this->translationAPI->__('Comment\'s content', 'pop-comments'),
            'authorName' => $this->translationAPI->__('Comment author\'s name', 'pop-comments'),
            'authorURL' => $this->translationAPI->__('Comment author\'s URL', 'pop-comments'),
            'authorEmail' => $this->translationAPI->__('Comment author\'s email', 'pop-comments'),
            'customPost' => $this->translationAPI->__('Custom post to which the comment was added', 'pop-comments'),
            'customPostID' => $this->translationAPI->__('ID of the custom post to which the comment was added', 'pop-comments'),
            'approved' => $this->translationAPI->__('Is the comment approved?', 'pop-comments'),
            'type' => $this->translationAPI->__('Type of comment', 'pop-comments'),
            'parent' => $this->translationAPI->__('Parent comment (if this comment is a response to another one)', 'pop-comments'),
            'date' => $this->translationAPI->__('Date when the comment was added', 'pop-comments'),
            'responses' => $this->translationAPI->__('Responses to the comment', 'pop-comments'),
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
        $dateFormatter = DateFormatterFacade::getInstance();
        $comment = $resultItem;
        switch ($fieldName) {
            case 'content':
                return $this->commentTypeAPI->getCommentContent($comment);

            case 'authorName':
                return $this->commentTypeAPI->getCommentAuthorName($comment);

            case 'authorURL':
                return $this->commentTypeAPI->getCommentAuthorURL($comment);

            case 'authorEmail':
                return $this->commentTypeAPI->getCommentAuthorEmail($comment);

            case 'customPost':
            case 'customPostID':
                return $this->commentTypeAPI->getCommentPostId($comment);

            case 'approved':
                return $this->commentTypeAPI->isCommentApproved($comment);

            case 'type':
                return $this->commentTypeAPI->getCommentType($comment);

            case 'parent':
                return $this->commentTypeAPI->getCommentParent($comment);

            case 'date':
                return $dateFormatter->format(
                    $fieldArgs['format'],
                    $this->commentTypeAPI->getCommentDateGmt($comment)
                );

            case 'responses':
                $query = array(
                    'status' => Status::APPROVED,
                    // The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
                    'order' =>  'ASC',
                    'orderby' => $this->nameResolver->getName('popcms:dbcolumn:orderby:comments:date'),
                    'parentID' => $typeResolver->getID($comment),
                );
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $this->commentTypeAPI->getComments($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'date':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('Date format, as defined in %s', 'pop-comments'),
                                'https://www.php.net/manual/en/function.date.php'
                            ),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->cmsService->getOption($this->nameResolver->getName('popcms:option:dateFormat')),
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
            case 'responses':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
