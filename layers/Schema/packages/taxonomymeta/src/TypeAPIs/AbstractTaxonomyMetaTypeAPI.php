<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\TypeAPIs;

use PoP\Root\Managers\ComponentManager;
use InvalidArgumentException;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPSchema\TaxonomyMeta\Component;
use PoPSchema\TaxonomyMeta\ComponentConfiguration;

abstract class AbstractTaxonomyMetaTypeAPI extends AbstractMetaTypeAPI implements TaxonomyMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException
     */
    final public function getTaxonomyTermMeta(string | int $termID, string $key, bool $single = false, array $options = []): mixed
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetTaxonomyMeta($termID, $key, $single);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed;
}
