<?php

declare(strict_types=1);

namespace App\Helpers;

use Nette\Database\Table\ActiveRow;
use Nette\Utils\Html;

class TaskResultBadgeHelper
{
    public function process(ActiveRow $parallelTask): Html
    {
        if ($parallelTask['error_count']) {
            return Html::el('span', ['class' => 'badge badge-success'])->setText('Error');
        }

        if ($parallelTask['success_count']) {
            return Html::el('span', ['class' => 'badge badge-success'])->setText('Success');
        }

        if ($parallelTask['skip_count']) {
            return Html::el('span', ['class' => 'badge badge-warning'])->setText('All skipped');
        }

        return Html::el('span', ['class' => 'badge badge-secondary'])->setText('Unknown');
    }
}
