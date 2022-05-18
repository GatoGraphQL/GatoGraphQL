<?php

class PoPThemeWassup_UserStance_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const MODULE_LATESTCOUNT_STANCES = 'latestcount-stances';
    public final const MODULE_LATESTCOUNT_AUTHOR_STANCES = 'latestcount-author-stances';
    public final const MODULE_LATESTCOUNT_TAG_STANCES = 'latestcount-tag-stances';
    public final const MODULE_LATESTCOUNT_SINGLE_STANCES = 'latestcount-single-stances';
    public final const MODULE_LATESTCOUNT_STANCES_PRO = 'latestcount-stances-pro';
    public final const MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO = 'latestcount-author-stances-pro';
    public final const MODULE_LATESTCOUNT_TAG_STANCES_PRO = 'latestcount-tag-stances-pro';
    public final const MODULE_LATESTCOUNT_SINGLE_STANCES_PRO = 'latestcount-single-stances-pro';
    public final const MODULE_LATESTCOUNT_STANCES_AGAINST = 'latestcount-stances-against';
    public final const MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST = 'latestcount-author-stances-against';
    public final const MODULE_LATESTCOUNT_TAG_STANCES_AGAINST = 'latestcount-tag-stances-against';
    public final const MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST = 'latestcount-single-stances-against';
    public final const MODULE_LATESTCOUNT_STANCES_NEUTRAL = 'latestcount-stances-neutral';
    public final const MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL = 'latestcount-author-stances-neutral';
    public final const MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL = 'latestcount-tag-stances-neutral';
    public final const MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL = 'latestcount-single-stances-neutral';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_STANCES],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_STANCES],
            [self::class, self::MODULE_LATESTCOUNT_TAG_STANCES],
            [self::class, self::MODULE_LATESTCOUNT_SINGLE_STANCES],
            [self::class, self::MODULE_LATESTCOUNT_STANCES_PRO],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO],
            [self::class, self::MODULE_LATESTCOUNT_TAG_STANCES_PRO],
            [self::class, self::MODULE_LATESTCOUNT_SINGLE_STANCES_PRO],
            [self::class, self::MODULE_LATESTCOUNT_STANCES_AGAINST],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST],
            [self::class, self::MODULE_LATESTCOUNT_TAG_STANCES_AGAINST],
            [self::class, self::MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST],
            [self::class, self::MODULE_LATESTCOUNT_STANCES_NEUTRAL],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL],
            [self::class, self::MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL],
            [self::class, self::MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL],
        );
    }

    public function getObjectName(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_STANCES:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES:
            case self::MODULE_LATESTCOUNT_TAG_STANCES:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES:
            case self::MODULE_LATESTCOUNT_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                return PoP_UserStance_PostNameUtils::getNameLc();
        }
    
        return parent::getObjectNames($componentVariation, $props);
    }

    public function getObjectNames(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_STANCES:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES:
            case self::MODULE_LATESTCOUNT_TAG_STANCES:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES:
            case self::MODULE_LATESTCOUNT_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                return PoP_UserStance_PostNameUtils::getNamesLc();
        }
    
        return parent::getObjectNames($componentVariation, $props);
    }

    public function getSectionClasses(array $componentVariation, array &$props)
    {
        $ret = parent::getSectionClasses($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_STANCES:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES:
            case self::MODULE_LATESTCOUNT_TAG_STANCES:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_PRO;
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_AGAINST;
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_NEUTRAL;
                break;

            case self::MODULE_LATESTCOUNT_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_PRO:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_PRO;
                break;

            case self::MODULE_LATESTCOUNT_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_AGAINST;
                break;

            case self::MODULE_LATESTCOUNT_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                $ret[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_NEUTRAL;
                break;
        }
    
        return $ret;
    }

    public function isAuthor(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_AUTHOR_STANCES_NEUTRAL:
                return true;
        }
    
        return parent::isAuthor($componentVariation, $props);
    }

    public function isTag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_TAG_STANCES:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_TAG_STANCES_NEUTRAL:
                return true;
        }
    
        return parent::isTag($componentVariation, $props);
    }

    public function isSingle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_PRO:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_AGAINST:
            case self::MODULE_LATESTCOUNT_SINGLE_STANCES_NEUTRAL:
                return true;
        }
    
        return parent::isSingle($componentVariation, $props);
    }
}


