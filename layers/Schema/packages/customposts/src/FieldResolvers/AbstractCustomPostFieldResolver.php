<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
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
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'content':
                $format = $fieldArgs['format'];
                $value = '';
                if ($format == CustomPostContentFormatEnum::HTML) {
                    $value = $customPostTypeAPI->getContent($customPost);
                } elseif ($format == CustomPostContentFormatEnum::PLAIN_TEXT) {
                    $value = $customPostTypeAPI->getPlainTextContent($customPost);
                }
                return HooksAPIFacade::getInstance()->applyFilters(
                    'pop_content',
                    $value,
                    $typeResolver->getID($customPost)
                );

            case 'url':
                return $customPostTypeAPI->getPermalink($customPost);

            case 'slug':
                return $customPostTypeAPI->getSlug($customPost);

            case 'status':
                return $customPostTypeAPI->getStatus($customPost);

            case 'isStatus':
                return $fieldArgs['status'] == $customPostTypeAPI->getStatus($customPost);

            case 'date':
                return $cmsengineapi->getDate($fieldArgs['format'], $customPostTypeAPI->getPublishedDate($customPost));

            case 'datetime':
                // If it is the current year, don't add the year. Otherwise, do
                // 15 Jul, 21:47 or // 15 Jul 2018, 21:47
                $date = $customPostTypeAPI->getPublishedDate($customPost);
                $format = $fieldArgs['format'];
                if (!$format) {
                    $format = ($cmsengineapi->getDate('Y', $date) == date('Y')) ? 'j M, H:i' : 'j M Y, H:i';
                }
                return $cmsengineapi->getDate($format, $date);

            case 'title':
                return $customPostTypeAPI->getTitle($customPost);

            case 'excerpt':
                return $customPostTypeAPI->getExcerpt($customPost);

            case 'customPostType':
                return $customPostTypeAPI->getCustomPostType($customPost);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
