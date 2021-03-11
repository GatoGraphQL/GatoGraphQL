<?php

declare(strict_types=1);

namespace PoPSchema\CDNDirective\DirectiveResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CDNDirective\DirectiveResolvers\ModifyURLDirectiveResolver;
use PoPSchema\CDNDirective\ComponentConfiguration;

/**
 * Replace a starter section from the URL with the CDN URL
 */
class CDNDirectiveResolver extends ModifyURLDirectiveResolver
{
    const DIRECTIVE_NAME = 'cdn';
    public function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function getFromURLSection(): ?string
    {
        /**
         * Prioritize the value passed by directive argument.
         * If none, use the environment value
         */
        if ($from = parent::getFromURLSection()) {
            return $from;
        }
        return ComponentConfiguration::getFromURLSection();
    }
    protected function getToURLSection(): ?string
    {
        /**
         * Prioritize the value passed by directive argument.
         * If none, use the environment value
         */
        if ($to = parent::getToURLSection()) {
            return $to;
        }
        return ComponentConfiguration::getToURLSection();
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $directiveArgs = parent::getSchemaDirectiveArgs($typeResolver);
        /**
         * If environment variables were provided, make the arguments non-mandatory
         */
        $nonMandatoryDirectiveArgs = [];
        if (ComponentConfiguration::getFromURLSection()) {
            $nonMandatoryDirectiveArgs[] = 'from';
        }
        if (ComponentConfiguration::getToURLSection()) {
            $nonMandatoryDirectiveArgs[] = 'to';
        }
        if ($nonMandatoryDirectiveArgs) {
            foreach ($directiveArgs as &$directiveArg) {
                if (in_array($directiveArg[SchemaDefinition::ARGNAME_NAME], $nonMandatoryDirectiveArgs)) {
                    $directiveArg[SchemaDefinition::ARGNAME_MANDATORY] = false;
                }
            }
        }
        return $directiveArgs;
    }
}
