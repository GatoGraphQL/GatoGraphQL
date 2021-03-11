<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
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
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if (
            CDNComponentConfiguration::getFromURLSection() &&
            CDNComponentConfiguration::getToURLSection()
        ) {
            // Add the mapping
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var DirectiveResolverInterface */
            $cdnDirectiveResolver = $instanceManager->getInstance(CDNDirectiveResolver::class);
            $cdnDirective = $fieldQueryInterpreter->getDirective(
                $cdnDirectiveResolver->getDirectiveName()
            );
            $mandatoryDirectivesForFields['src'] = [
                $cdnDirective,
            ];
        }
        return $mandatoryDirectivesForFields;
    }
}
