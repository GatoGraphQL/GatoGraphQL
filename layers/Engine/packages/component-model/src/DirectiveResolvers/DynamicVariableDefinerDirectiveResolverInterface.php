<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

interface DynamicVariableDefinerDirectiveResolverInterface extends DirectiveResolverInterface
{
    /**
     * Name for the directive arg to indicate the name of the
     * dynamic variable.
     *
     * Eg: @export(as: "variableName")
     */
    public function getExportUnderVariableNameArgumentName(): string;
}
