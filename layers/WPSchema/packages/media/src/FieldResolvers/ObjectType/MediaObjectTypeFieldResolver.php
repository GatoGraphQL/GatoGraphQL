<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

class MediaObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected CMSHelperServiceInterface $cmsHelperService;
    protected DateFormatterInterface $dateFormatter;
    protected QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver;

    #[Required]
    public function autowireMediaObjectTypeFieldResolver(
        CMSHelperServiceInterface $cmsHelperService,
        DateFormatterInterface $dateFormatter,
        QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver,
    ): void {
        $this->cmsHelperService = $cmsHelperService;
        $this->dateFormatter = $dateFormatter;
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->queryableInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->translationAPI->__('Media element URL', 'pop-media'),
            'urlPath' => $this->translationAPI->__('Media element URL path', 'pop-media'),
            'slug' => $this->translationAPI->__('Media element slug', 'pop-media'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
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
        /** @var WP_Post */
        $mediaItem = $object;
        switch ($fieldName) {
            case 'url':
            case 'urlPath':
                $url = \get_permalink($mediaItem->ID);
                if ($fieldName === 'url') {
                    return $url;
                }
                /** @var string */
                return $this->cmsHelperService->getLocalURLPath($url);
            case 'slug':
                return $mediaItem->post_name;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
