<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TaskRecordTypeChart\TaskRecordTypeChart;
use Nette\Application\BadRequestException;
use Nette\Database\Table\Selection;
use Nette\Utils\Strings;
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
        $this->getComponent('grid')->setDataSource($this->db->table($task['table_name']));

        $this->template->task = $task;
        $this->template->allRecords = $this->db->table($task['table_name'])->count('*');
        $this->template->groupedResultsByMessage = $this->db->table($task['table_name'])->group('message, type')->select("message, type, COUNT('*') AS cnt")->order('cnt DESC')->fetchAll();
    }

    protected function createComponentTaskRecordTypeChart(): TaskRecordTypeChart
    {
        return new TaskRecordTypeChart($this->pageScripts);
    }

    protected function createComponentGrid($name)
    {
        $grid = new DataGrid($this, $name);
        $grid->setItemsPerPageList([50, 100]);
        $nowrap = [
            'style' => [
                'white-space: nowrap',
                'font-size: 14px',
            ]
        ];

        $grid->addColumnText('type', 'Type')
            ->setTemplateEscaping(false)
            ->setRenderer(function($item) {
                if ($item['type'] === 'success') {
                    return '<span class="badge badge-success">' . $item['type'] . '</span>';
                }
                if ($item['type'] === 'skip') {
                    return '<span class="badge badge-warning">' . $item['type'] . '</span>';
                }
                if ($item['type'] === 'error') {
                    return '<span class="badge badge-danger">' . $item['type'] . '</span>';
                }
                return '<span class="badge badge-secondary">' . $item['type'] . '</span>';
            });
        $grid->addColumnText('message', 'Message')
            ->addCellAttributes($nowrap);
        $grid->addColumnText('data', 'Data')
            ->addCellAttributes(['style' => ['font-size: 14px']])
            ->setTemplateEscaping(false)
            ->setRenderer(function($item) {
                return sprintf('<code>%s</code>', $item['data']);
            });
        $grid->addColumnText('record_id', 'Record ID')
            ->addCellAttributes($nowrap);

        $grid->addFilterSelect('type', 'Type', [
            '' => 'all',
            'success' => 'success',
            'skip' => 'skip',
            'error' => 'error'
        ]);
        $grid->addFilterText('message', 'Message');
        $grid->addFilterText('data', 'Data')
            ->setCondition(function(Selection $fluent, $value) {
                $exploded = explode(':', $value);

                if (count($exploded) !== 2) {
                    return;
                }

                $column = trim($exploded[0]);
                $value = trim($exploded[1]);
                $sign = '=';

                foreach (['=', '>', '>=', '<=', '<', '!=', '<>', 'LIKE'] as $operand) {
                    if (strpos($value, $operand) === 0) {
                        $sign = $operand;
                        $value = trim(substr($value, strlen($operand)));
                        break;
                    }
                }

                if (!ctype_digit($value)) {
                    $value = "'" . $value . "'";
                }

                $fluent->where('JSON_EXTRACT(data, "$.' . $column . '") ' . $sign . ' '. $value);
            });
        $grid->addFilterText('record_id', 'Record ID');
    }
}
