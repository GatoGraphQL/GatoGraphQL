<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMediaMutations\ConditionalOnModule\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType\AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class WithFeaturedImagePageObjectTypeFieldResolver extends AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver
{
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
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
