<?php
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoPCMSSchema\UserState\Checkpoints\DoingPostUserLoggedInAggregateCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;

trait PoP_UserStance_Module_SettingsProcessor_Trait
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?DoingPostUserLoggedInAggregateCheckpoint $doingPostUserLoggedInAggregateCheckpoint = null;

    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
    }
    final public function setDoingPostUserLoggedInAggregateCheckpoint(DoingPostUserLoggedInAggregateCheckpoint $doingPostUserLoggedInAggregateCheckpoint): void
    {
        $this->doingPostUserLoggedInAggregateCheckpoint = $doingPostUserLoggedInAggregateCheckpoint;
    }
    final protected function getDoingPostUserLoggedInAggregateCheckpoint(): DoingPostUserLoggedInAggregateCheckpoint
    {
        return $this->doingPostUserLoggedInAggregateCheckpoint ??= $this->instanceManager->getInstance(DoingPostUserLoggedInAggregateCheckpoint::class);
    }
    
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERSTANCE_ROUTE_MYSTANCES,
                POP_USERSTANCE_ROUTE_ADDSTANCE,
                POP_USERSTANCE_ROUTE_EDITSTANCE,
                POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
                POP_USERSTANCE_ROUTE_STANCES,
                POP_USERSTANCE_ROUTE_STANCES_PRO,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
                POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
                POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
                POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
                POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_USERSTANCE_ROUTE_ADDSTANCE => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_USERSTANCE_ROUTE_MYSTANCES => [$this->getUserLoggedInCheckpoint()],
            POP_USERSTANCE_ROUTE_EDITSTANCE => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,
            // If first loading the page: do not let it fail checkpoint validation, hence LOGGEDIN_STATIC. However, it must always get the data from the server, hence REQUIREUSERSTATE
            // When doing a submit, handle it as the usual LOGGEDIN_DATAFROMSERVER
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => doingPost() ?
                [$this->getUserLoggedInCheckpoint()] : 
                [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_USERSTANCE_ROUTE_EDITSTANCE => true,
        );
    }
}
