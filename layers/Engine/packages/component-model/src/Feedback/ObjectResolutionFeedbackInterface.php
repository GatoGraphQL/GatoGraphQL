<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

interface ObjectResolutionFeedbackInterface extends QueryFeedbackInterface
{
    public function getDirective(): Directive;
    public function getRelationalTypeResolver(): RelationalTypeResolverInterface;
    /**
     * @return array<string|int,EngineIterationFieldSet>
     */
    public function getIDFieldSet(): array;
}
