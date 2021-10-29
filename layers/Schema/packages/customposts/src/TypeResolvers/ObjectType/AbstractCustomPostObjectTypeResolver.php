<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostObjectTypeResolver extends AbstractObjectTypeResolver implements CustomPostObjectTypeResolverInterface
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->getInstanceManager()->getInstance(CustomPostTypeAPIInterface::class);
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getID(object $object): string | int | null
    {
        return $this->getCustomPostTypeAPI()->getID($object);
    }
}
