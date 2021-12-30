<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

interface MetaDirectiveResolverInterface extends DirectiveResolverInterface
{
    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @translate
     */
    public function getAffectDirectivesUnderPosArgumentName(): string;
    /**
     * @return int[]|null
     */
    public function getAffectDirectivesUnderPosArgumentDefaultValue(): ?array;
}
