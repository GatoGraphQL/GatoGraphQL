<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\Root\Translation\TranslationAPIInterface;

trait WithVersionConstraintFieldOrFieldDirectiveResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    protected function getVersionConstraintFieldOrDirectiveArgDescription(): string
    {
        return $this->getTranslationAPI()->__('The version to restrict to, using the semantic versioning constraint rules used by Composer (https://getcomposer.org/doc/articles/versions.md)', 'component-model');
    }
}
