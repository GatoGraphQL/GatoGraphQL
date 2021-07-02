<?php

class PoP_ServiceWorkers_JobManager
{
    public $jobs;

    public function __construct()
    {
        return $this->jobs = array();
    }

    public function add($scope, $job)
    {
        if (!isset($this->jobs[$scope])) {
            $this->jobs[$scope] = array();
        }
        $this->jobs[$scope][] = $job;
    }

    public function get($scope)
    {
        return $this->jobs[$scope];
    }

    public function getDependencies($scope)
    {
        $dependencies = array();
        foreach ($this->jobs[$scope] as $job) {
            $dependencies = array_merge(
                $dependencies,
                $job->getDependencies()
            );
        }

        return $dependencies;
    }

    public function renderSw($scope)
    {
        $parts = array();

        // Comment Leo 26/12/2016: No need to add the dependencies here, the actual files are instead copied to the dependencies folder
        // // Add the dependencies at the beginning (eg: localforage)
        // // They have a key so different jobs can override, or at least not duplicate, the same dependencies
        // foreach ($this->getDependencies($scope) as $key => $dependency) {

        //     $parts[] = file_get_contents($dependency);
        // }

        foreach ($this->jobs[$scope] as $job) {
            $parts[] = $this->render($job->getSwJsPath(), $job->getSwConfiguration(), $job->getSwCodereplacements());
        }
        return implode(/*';'*/PHP_EOL, $parts);
    }

    private function render($path, $replacements, $codereplacements)
    {
        $contents = file_get_contents($path);
        foreach ($replacements as $key => $replacement) {
            $value = json_encode($replacement);
            $contents = str_replace($key, $value, $contents);
        }
        foreach ($codereplacements as $key => $replacement) {
            $value = $replacement;
            $contents = str_replace($key, $value, $contents);
        }
        return $contents;
    }
}

/**
 * Initialize
 */
global $pop_serviceworkers_job_manager;
$pop_serviceworkers_job_manager = new PoP_ServiceWorkers_JobManager();
