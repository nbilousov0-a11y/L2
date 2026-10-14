<?php
// app/Controllers/ProjectController.php — контроллер проектов: список и просмотр проекта с его задачами.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProjectRepository;
use App\Models\TaskRepository;

final class ProjectController
{
    private ProjectRepository $projects;
    private TaskRepository $tasks;

    public function __construct()
    {
        $this->projects = new ProjectRepository();
        $this->tasks = new TaskRepository();
    }

    // GET /projects
    public function index(): void
    {
        view('projects/index', [
            'title'    => 'Проекты',
            'projects' => $this->projects->all(),
        ]);
    }

    // GET /projects/2
    public function show(string $id): void
    {
        $project = $this->projects->find((int) $id);
        if ($project === null) {
            notFound();
        }

        view('projects/show', [
            'title'   => $project['title'],
            'project' => $project,
            'tasks'   => $this->tasks->byProject($project['id']),
        ]);
    }
}