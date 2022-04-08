<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Component;
use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait AccessControlConfigurableMandatoryDirectivesForDirectivesHookSetTrait
{
    public function maybeFilterDirectiveName(bool $include, RelationalTypeResolverInterface $relationalTypeResolver, DirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        /**
         * If not enabling individual control, then the parent case already deals with the general case
         */
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableIndividualControlForPublicPrivateSchemaMode()) {
            return parent::maybeFilterDirectiveName($include, $relationalTypeResolver, $directiveResolver, $directiveName);
        }

        /**
         * On the entries we will resolve either the class of the directive resolver, or any of its ancestors
         * If there is any entry for this directive resolver, after filtering, then enable it
         * Otherwise, exit by returning the original hook value
         */
        $ancestorDirectiveResolverClasses = [];
        $directiveResolverClass = get_class($directiveResolver);
        do {
            $ancestorDirectiveResolverClasses[] = $directiveResolverClass;
            $directiveResolverClass = get_parent_class($directiveResolverClass);
        } while ($directiveResolverClass !== null);
        $entries = $this->getEntries();
        foreach ($entries as $entry) {
            /**
             * If there is any entry for this directive, then continue the normal execution: that of the parent
             */
            if (in_array($entry[0], $ancestorDirectiveResolverClasses)) {
                return parent::maybeFilterDirectiveName($include, $relationalTypeResolver, $directiveResolver, $directiveName);
            }
        }
        /**
         * If there are no entries, then exit
         */
        return $include;
    }
}
