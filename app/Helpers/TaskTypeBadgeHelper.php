<?php

declare(strict_types=1);

namespace App\Helpers;

use Nette\Utils\Html;

class TaskTypeBadgeHelper
{
    public function process(string $type): Html
    {
        if ($type === 'success') {
            return Html::el('span', ['class' => 'badge badge-success'])->setText($type);
        }

        if ($type === 'skip') {
            return Html::el('span', ['class' => 'badge badge-warning'])->setText($type);
        }

        if ($type === 'error') {
            return Html::el('span', ['class' => 'badge badge-danger'])->setText($type);
        }

        return Html::el('span', ['class' => 'badge badge-secondary'])->setText($type);
    }
}
