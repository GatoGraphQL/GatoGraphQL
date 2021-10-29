<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait PostTagAPISatisfiedContractTrait
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;

    public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }
    public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }

    //#[Required]
    public function autowirePostTagAPISatisfiedContractTrait(
        PostTagTypeAPIInterface $postTagTypeAPI,
        PostTagObjectTypeResolver $postTagObjectTypeResolver,
    ): void {
        $this->postTagTypeAPI = $postTagTypeAPI;
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }

    public function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
}
