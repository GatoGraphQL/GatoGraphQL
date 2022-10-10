<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

interface MetaFieldDirectiveResolverInterface extends FieldDirectiveResolverInterface
{
    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @strTranslate
     */
    public function getAffectDirectivesUnderPosArgumentName(): string;
    /**
     * @return int[]
     */
    public function getAffectDirectivesUnderPosArgumentDefaultValue(): array;
}
