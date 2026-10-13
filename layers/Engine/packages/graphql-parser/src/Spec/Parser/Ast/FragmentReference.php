<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class FragmentReference extends AbstractAst implements FragmentBondInterface, WithDirectivesInterface
{
    use WithDirectivesTrait;

    /**
     * @param Directive[] $directives
     */
    public function __construct(
        protected readonly string $name,
        Location $location,
        array $directives = [],
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    protected function doAsQueryString(): string
    {
        $strDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = ' ' . implode(
                ' ',
                array_map(
                    fn (Directive $directive) => $directive->asQueryString(),
                    $this->directives
                )
            );
        }
        return sprintf(
            '...%s%s',
            $this->name,
            $strDirectives
        );
    }

    protected function doAsASTNodeString(): string
    {
        return sprintf(
            '...%s',
            $this->name
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
