<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractObjectFeedback extends AbstractSchemaFeedback implements ObjectFeedbackInterface
{
    public function __construct(
        string $message,
        string $code,
        Location $location,
        array $data = [],
        array $extensions = [],
        RelationalTypeResolverInterface $relationalTypeResolver,
        /** @var string[] */
        array $fields,
        /** @var array<string|int> */
        protected array $objectIDs,
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
            $extensions,
            $relationalTypeResolver,
            $fields,
        );
    }
    
    /**
     * @return array<string|int>
     */
    public function getObjectIDs(): array
    {
        return $this->objectIDs;
    }
}
