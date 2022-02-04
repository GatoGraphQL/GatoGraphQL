<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class ObjectFeedback extends AbstractQueryFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        string $message,
        ?string $code,
        Location $location,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var string[] */
        protected array $fields,
        /** @var array<string|int> */
        protected array $objectIDs,
        /** @var array<string, mixed> */
        array $extensions = [],
        /** @var array<string, mixed> */
        array $data = [],
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $extensions,
            $data,
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array<string|int>
     */
    public function getObjectIDs(): array
    {
        return $this->objectIDs;
    }
}
