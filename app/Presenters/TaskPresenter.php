<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TaskRecordTypeChart\TaskRecordTypeChart;
use Nette\Application\BadRequestException;
use Ublaboo\DataGrid\DataGrid;

final class TaskPresenter extends BasePresenter
{
    public function actionDetail(int $id)
    {
        $task = $this->db->table('parallel_tasks')->get($id);
        if (!$task) {
            return new BadRequestException();
        }

        $this->getComponent('taskRecordTypeChart')->set($task['success_count'], $task['skip_count'], $task['error_count']);
        $this->getComponent('sidebar')->setActive($task['id']);
//        $this->getComponent('grid')->setDataSource($this->db->table($task['table_name']));

        $this->template->task = $task;
        $this->template->records = $this->db->table($task['table_name'])->limit(100)->fetchAll();
        $this->template->allRecords = $this->db->table($task['table_name'])->count('*');
        $this->template->groupedResultsByMessage = $this->db->table($task['table_name'])->group('message, type')->select("message, type, COUNT('*') AS cnt")->order('cnt DESC')->fetchAll();
    }

    protected function createComponentTaskRecordTypeChart(): TaskRecordTypeChart
    {
        return new TaskRecordTypeChart($this->pageScripts);
    }

    protected function createComponentGrid($name)
    {
//        $grid = new DataGrid($this, $name);
//
//        $grid->addColumnText('type', 'Type');
//        $grid->addColumnText('message', 'Message');
//        $grid->addColumnText('data', 'Data');
//        $grid->addColumnText('record_id', 'Record ID');
    }
}
