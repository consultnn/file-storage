<?php

declare(strict_types=1);

namespace app\components\project;
use app\exceptions\ProjectNotSetException;

/**
 * Class Project
 * @package app\components
 */
class ProjectList
{
    /**
     * @var Project[]
     */
    private $projects;

    /**
     * @var Project
     */
    private $activeProject;

    /**
     * ProjectList constructor.
     * @param array $projectsSettings
     */
    public function __construct(array $projectsSettings)
    {
        foreach ($projectsSettings as $projectName => $projectData) {
            $projectInstance = new Project(
                $projectName,
                $projectData['uploadToken'],
                $projectData['downloadSignKey'],
                $projectData['storage']
            );

            $this->add($projectInstance);
        }
    }

    /**
     * @param Project $projectInstance
     */
    private function add(Project $projectInstance): void
    {
        $this->projects[$projectInstance->getName()] = $projectInstance;
    }

    /**
     * @param string $projectName
     * @return Project
     */
    public function get(string $projectName): Project
    {
        if (!isset($this->projects[$projectName])) {
            throw new ProjectNotSetException();
        }

        return $this->projects[$projectName];
    }

    /**
     * @param Project $project
     */
    public function setActiveProject(Project $project): void
    {
        $this->activeProject = $project;
    }

    /**
     * @return Project
     */
    public function getActiveProject(): Project
    {
        return $this->activeProject;
    }
}
