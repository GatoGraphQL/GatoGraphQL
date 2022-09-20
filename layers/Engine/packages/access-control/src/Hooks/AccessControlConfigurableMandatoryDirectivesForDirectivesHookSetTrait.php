<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Module;
use PoP\AccessControl\ModuleConfiguration;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait AccessControlConfigurableMandatoryDirectivesForDirectivesHookSetTrait
{
    public function maybeFilterDirectiveName(bool $include, RelationalTypeResolverInterface $relationalTypeResolver, FieldDirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        /**
         * If not enabling individual control, then the parent case already deals with the general case
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableIndividualControlForPublicPrivateSchemaMode()) {
            return parent::maybeFilterDirectiveName($include, $relationalTypeResolver, $directiveResolver, $directiveName);
        }

        /**
         * On the entries we will resolve either the class of the directive resolver, or any of its ancestors
         * If there is any entry for this directive resolver, after filtering, then enable it
         * Otherwise, exit by returning the original hook value
         */
        $ancestorFieldDirectiveResolverClasses = [];
        $directiveResolverClass = get_class($directiveResolver);
        do {
            $ancestorFieldDirectiveResolverClasses[] = $directiveResolverClass;
            $directiveResolverClass = get_parent_class($directiveResolverClass);
        } while ($directiveResolverClass !== false);
        $entries = $this->getEntries();
        foreach ($entries as $entry) {
            /**
             * If there is any entry for this directive, then continue the normal execution: that of the parent
             */
            if (in_array($entry[0], $ancestorFieldDirectiveResolverClasses)) {
                return parent::maybeFilterDirectiveName($include, $relationalTypeResolver, $directiveResolver, $directiveName);
            }
        }
        /**
         * If there are no entries, then exit
         */
        return $include;
    }
}
