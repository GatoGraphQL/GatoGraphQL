<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers;

use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Comments\FieldInterfaceResolvers\CommentableFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CustomPostFieldResolver extends AbstractQueryableFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public static function getImplementedInterfaceClasses(): array
    {
        return [
            CommentableFieldInterfaceResolver::class,
        ];
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'areCommentsOpen',
            'commentCount',
            'hasComments',
            'comments',
        ];
    }

    /**
     * By returning `null`, the schema definition comes from the interface
     *
     * @return void
     */
    public function getSchemaDefinitionResolver(TypeResolverInterface $typeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return null;
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
        $cmscommentsapi = \PoPSchema\Comments\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'areCommentsOpen':
                return $cmscommentsapi->areCommentsOpen($typeResolver->getID($post));

            case 'commentCount':
                return $cmscommentsapi->getCommentNumber($typeResolver->getID($post));

            case 'hasComments':
                return $typeResolver->resolveValue($post, 'commentCount', $variables, $expressions, $options) > 0;

            case 'comments':
                $query = array(
                    'status' => POP_COMMENTSTATUS_APPROVED,
                    // 'type' => 'comment', // Only comments, no trackbacks or pingbacks
                    'customPostID' => $typeResolver->getID($post),
                    // The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
                    'order' =>  'ASC',
                    'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:comments:date'),
                );
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $cmscommentsapi->getComments($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'comments':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
