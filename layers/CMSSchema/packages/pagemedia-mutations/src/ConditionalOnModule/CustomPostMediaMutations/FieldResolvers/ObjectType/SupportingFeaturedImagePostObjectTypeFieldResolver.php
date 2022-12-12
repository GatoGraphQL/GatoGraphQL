<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType\AbstractSupportingFeaturedImageCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class SupportingFeaturedImagePageObjectTypeFieldResolver extends AbstractSupportingFeaturedImageCustomPostObjectTypeFieldResolver
{
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        /** @var PageTypeAPIInterface */
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PageObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostType(): string
    {
        return $this->getPageTypeAPI()->getPageCustomPostType();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setFeaturedImage' => $this->__('Set the featured image on the page', 'pagemedia-mutations'),
            'removeFeaturedImage' => $this->__('Remove the featured image on the page', 'pagemedia-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }
}
