<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ConditionalOnModule\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * Please notice: It extends a class under ConditionalOnModule/Users/
 * but there's no need to do the same, as Users will already exist
 * for Mutations packages
 */
class PageObjectTypeFieldResolver extends AbstractAddCommentToCustomPostObjectTypeFieldResolver
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

    public function getAddCommentFieldDescription(): string
    {
        return $this->__('Add a comment to the page', 'page-mutations');
    }
}
