<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CDNDirective\ComponentConfiguration as CDNComponentConfiguration;
use PoPSchema\CDNDirective\DirectiveResolvers\CDNDirectiveResolver;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;

class CDNTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return [
            MediaTypeResolver::class,
        ];
    }

    /**
     * If the from/to URLs were defined for the CDN, then attach the CDN directive
     * to the corresponding fields
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if (
            CDNComponentConfiguration::getFromURLSection() &&
            CDNComponentConfiguration::getToURLSection()
        ) {
            // Add the mapping
            /** @var DirectiveResolverInterface */
            $cdnDirectiveResolver = $this->instanceManager->getInstance(CDNDirectiveResolver::class);
            $cdnDirective = $this->fieldQueryInterpreter->getDirective(
                $cdnDirectiveResolver->getDirectiveName()
            );
            $mandatoryDirectivesForFields['src'] = [
                $cdnDirective,
            ];
        }
        return $mandatoryDirectivesForFields;
    }
}
