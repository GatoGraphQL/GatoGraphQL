<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;

abstract class AbstractCustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [];
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    /**
     * Get the Schema Definition from the Interface
     */
    protected function doGetSchemaDefinitionResolver(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): FieldSchemaDefinitionResolverInterface | FieldInterfaceSchemaDefinitionResolverInterface {

        switch ($fieldName) {
            case 'url':
            case 'urlPath':
            case 'slug':
                /** @var QueryableFieldInterfaceResolver */
                $resolver = $this->instanceManager->getInstance(QueryableFieldInterfaceResolver::class);
                return $resolver;
                
            case 'content':
            case 'status':
            case 'isStatus':
            case 'date':
            case 'modified':
            case 'title':
            case 'excerpt':
            case 'customPostType':
                /** @var IsCustomPostFieldInterfaceResolver */
                $resolver = $this->instanceManager->getInstance(IsCustomPostFieldInterfaceResolver::class);
                return $resolver;
        }

        return parent::doGetSchemaDefinitionResolver($relationalTypeResolver, $fieldName);
    }

    protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $dateFormatter = DateFormatterFacade::getInstance();
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'url':
                return $customPostTypeAPI->getPermalink($customPost);

            case 'urlPath':
                return $customPostTypeAPI->getPermalinkPath($customPost);

            case 'slug':
                return $customPostTypeAPI->getSlug($customPost);

            case 'content':
                $format = $fieldArgs['format'];
                $value = '';
                if ($format == CustomPostContentFormatEnum::HTML) {
                    $value = $customPostTypeAPI->getContent($customPost);
                } elseif ($format == CustomPostContentFormatEnum::PLAIN_TEXT) {
                    $value = $customPostTypeAPI->getPlainTextContent($customPost);
                }
                return $this->hooksAPI->applyFilters(
                    'pop_content',
                    $value,
                    $relationalTypeResolver->getID($customPost)
                );

            case 'status':
                return $customPostTypeAPI->getStatus($customPost);

            case 'isStatus':
                return $fieldArgs['status'] == $customPostTypeAPI->getStatus($customPost);

            case 'date':
                return $dateFormatter->format(
                    $fieldArgs['format'],
                    $customPostTypeAPI->getPublishedDate($customPost, $fieldArgs['gmt'])
                );

            case 'modified':
                return $dateFormatter->format(
                    $fieldArgs['format'],
                    $customPostTypeAPI->getModifiedDate($customPost, $fieldArgs['gmt'])
                );

            case 'title':
                return $customPostTypeAPI->getTitle($customPost);

            case 'excerpt':
                return $customPostTypeAPI->getExcerpt($customPost);

            case 'customPostType':
                return $customPostTypeAPI->getCustomPostType($customPost);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
