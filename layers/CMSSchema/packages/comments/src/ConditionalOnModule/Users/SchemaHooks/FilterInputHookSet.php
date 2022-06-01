<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\ComponentProcessors\FormInputs\FilterInputComponentProcessor as UserFilterInputComponentProcessor;
use PoPCMSSchema\Comments\ComponentProcessors\CommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;

class FilterInputHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CommentFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS,
            $this->getFilterInputComponents(...)
        );
    }

    /**
     * @param Component[] $filterInputComponents
     * @return Component[]
     */
    public function getFilterInputComponents(array $filterInputComponents): array
    {
        return [
            ...$filterInputComponents,
            ...$this->getAuthorFilterInputComponents(),
            ...$this->getCustomPostAuthorFilterInputComponents(),
        ];
    }

    /**
     * @return Component[]
     */
    public function getAuthorFilterInputComponents(): array
    {
        return [
            new Component(
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_AUTHOR_IDS
            ),
            new Component(
                FilterInputComponentProcessor::class,
                FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS
            ),
        ];
    }

    /**
     * @return Component[]
     */
    public function getCustomPostAuthorFilterInputComponents(): array
    {
        return [
            new Component(
                UserFilterInputComponentProcessor::class,
                UserFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS
            ),
            new Component(
                UserFilterInputComponentProcessor::class,
                UserFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
            ),
        ];
    }
}
