<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\DirectiveResolvers;

use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class MakeTitleDirectiveResolver extends MakeTitleVersion010DirectiveResolver
{
    public function getPriorityToAttachToClasses(): int
    {
        return 10;
    }

    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        return false;
    }

    public function resolveSchemaDirectiveWarningDescription(TypeResolverInterface $typeResolver): ?string
    {
        if (Environment::enableSemanticVersionConstraints()) {
            // If the query doesn't specify what version of the directive to use, add a deprecation message
            if (!$this->directiveArgsForSchema[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null) {
                return sprintf(
                    $this->translationAPI->__('Directive \'%1$s\' has a new version: \'%2$s\'. This version will become the default one on January 1st. We advise you to use this new version already and test that it works fine; if you find any problem, please report the issue in %3$s. To do the switch, please add the \'versionConstraint\' directive argument to your query, using Composer\'s semver constraint rules (%4$s): @%1$s(versionConstraint:"%5$s"). If you are unable to switch to the new version, please make sure to explicitly point to the current version \'%6$s\' before January 1st: @%1$s(versionConstraint:"%6$s"). In case of doubt, please contact us at name@company.com.', 'examples-for-pop'),
                    $this->getDirectiveName(),
                    '0.2.0',
                    'https://github.com/mycompany/myproject/issues',
                    'https://getcomposer.org/doc/articles/versions.md',
                    '^0.2',
                    '0.1.0'
                );
            }
        }
        return parent::resolveSchemaDirectiveWarningDescription($typeResolver);
    }
}
