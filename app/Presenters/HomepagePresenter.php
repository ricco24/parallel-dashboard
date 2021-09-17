<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\ChartPie\ChartPie;

final class HomepagePresenter extends BasePresenter
{
    public function actionDefault()
    {
        $this->template->setParameters([
            'tasks' => $this->db->table('parallel_tasks')->order('end_at ASC')->fetchAll(),
            'parallelStats' => $this->db->table('parallel_stats')->fetch(),
            'doneTasksCount' => $this->db->table('parallel_tasks')->count('*')
        ]);
    }
}
