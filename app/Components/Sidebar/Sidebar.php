<?php

namespace App\Components\Sidebar;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Database\Explorer;

class Sidebar extends Control
{
    private Explorer $db;

    private int $activeTaskId = 0;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    public function setActive(int $taskId): void
    {
        $this->activeTaskId = $taskId;
    }

    public function render()
    {
        $tasks = [];
        foreach ($this->db->table('parallel_tasks')->order('name ASC')->fetchAll() as $task) {
            if (strpos($task['name'], ':') === false) {
                $tasks['Global']['tasks'][] = $task;
                continue;
            }
            $group = explode(':', $task['name'])[0];
            $tasks[$group]['tasks'][] = $task;

            if ($task['id'] == $this->activeTaskId) {
                $tasks[$group]['active'] = true;
            }
        }

        /** @var Template $template */
        $template = $this->getTemplate();
        $template->setParameters([
            'tasks' => $tasks,
            'activeTaskId' => $this->activeTaskId,
        ]);
        $template->setFile(__DIR__ . '/default.latte');
        $template->render();
    }
}
