<?php

class PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const COMPONENT_LATESTCOUNT_STANCES = 'latestcount-stances';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_STANCES = 'latestcount-author-stances';
    public final const COMPONENT_LATESTCOUNT_TAG_STANCES = 'latestcount-tag-stances';
    public final const COMPONENT_LATESTCOUNT_SINGLE_STANCES = 'latestcount-single-stances';
    public final const COMPONENT_LATESTCOUNT_STANCES_PRO = 'latestcount-stances-pro';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO = 'latestcount-author-stances-pro';
    public final const COMPONENT_LATESTCOUNT_TAG_STANCES_PRO = 'latestcount-tag-stances-pro';
    public final const COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO = 'latestcount-single-stances-pro';
    public final const COMPONENT_LATESTCOUNT_STANCES_AGAINST = 'latestcount-stances-against';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST = 'latestcount-author-stances-against';
    public final const COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST = 'latestcount-tag-stances-against';
    public final const COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST = 'latestcount-single-stances-against';
    public final const COMPONENT_LATESTCOUNT_STANCES_NEUTRAL = 'latestcount-stances-neutral';
    public final const COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL = 'latestcount-author-stances-neutral';
    public final const COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL = 'latestcount-tag-stances-neutral';
    public final const COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL = 'latestcount-single-stances-neutral';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LATESTCOUNT_STANCES],
            [self::class, self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES],
            [self::class, self::COMPONENT_LATESTCOUNT_TAG_STANCES],
            [self::class, self::COMPONENT_LATESTCOUNT_SINGLE_STANCES],
            [self::class, self::COMPONENT_LATESTCOUNT_STANCES_PRO],
            [self::class, self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO],
            [self::class, self::COMPONENT_LATESTCOUNT_TAG_STANCES_PRO],
            [self::class, self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO],
            [self::class, self::COMPONENT_LATESTCOUNT_STANCES_AGAINST],
            [self::class, self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST],
            [self::class, self::COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST],
            [self::class, self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST],
            [self::class, self::COMPONENT_LATESTCOUNT_STANCES_NEUTRAL],
            [self::class, self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL],
            [self::class, self::COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL],
            [self::class, self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL],
        );
    }

    public function getObjectName(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_STANCES:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES:
            case self::COMPONENT_LATESTCOUNT_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                return PoP_UserStance_PostNameUtils::getNameLc();
        }
    
        return parent::getObjectNames($component, $props);
    }

    public function getObjectNames(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_STANCES:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES:
            case self::COMPONENT_LATESTCOUNT_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                return PoP_UserStance_PostNameUtils::getNamesLc();
        }
    
        return parent::getObjectNames($component, $props);
    }

    public function getSectionClasses(array $component, array &$props)
    {
        $ret = parent::getSectionClasses($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_STANCES:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_PRO;
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_AGAINST;
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_NEUTRAL;
                break;

            case self::COMPONENT_LATESTCOUNT_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_PRO;
                break;

            case self::COMPONENT_LATESTCOUNT_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_AGAINST;
                break;

            case self::COMPONENT_LATESTCOUNT_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_NEUTRAL;
                break;
        }
    
        return $ret;
    }

    public function isAuthor(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
                return true;
        }
    
        return parent::isAuthor($component, $props);
    }

    public function isTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_TAG_STANCES_NEUTRAL:
                return true;
        }
    
        return parent::isTag($component, $props);
    }

    public function isSingle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_PRO:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_AGAINST:
            case self::COMPONENT_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                return true;
        }
    
        return parent::isSingle($component, $props);
    }
}


