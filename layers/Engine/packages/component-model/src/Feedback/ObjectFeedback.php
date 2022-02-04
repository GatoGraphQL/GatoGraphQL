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
        /** @var array<string, mixed> */
        array $data = [],
        Location $location,
        /** @var array<string, mixed> */
        array $extensions = [],
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var string[] */
        protected array $fields,
        /** @var array<string|int> */
        protected array $objectIDs,
    ) {
        parent::__construct(
            $message,
            $code,
            $data,
            $location,
            $extensions,
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
