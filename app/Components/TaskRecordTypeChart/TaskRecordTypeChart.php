<?php

namespace App\Components\TaskRecordTypeChart;

use App\Models\PageScripts;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;

class TaskRecordTypeChart extends Control
{
    private PageScripts $pageScripts;

    private int $successCount = 0;

    private int $skipCount = 0;

    private int $errorCount = 0;

    public function __construct(PageScripts $pageScripts)
    {
        $this->pageScripts = $pageScripts;
    }

    public function set(int $successCount, int $skipCount, int $errorCount)
    {
        $this->successCount = $successCount;
        $this->skipCount = $skipCount;
        $this->errorCount = $errorCount;
    }

    public function render()
    {
        /** @var Template $template */
        $scriptTemplate = $this->getTemplate();
        $scriptTemplate->setFile(__DIR__ . '/script.latte');
        $scriptTemplate->setParameters([
            'successCount' => $this->successCount,
            'skipCount' => $this->skipCount,
            'errorCount' => $this->errorCount,
        ]);

        $this->pageScripts->addScript($scriptTemplate->renderToString());

        /** @var Template $template */
        $template = $this->getTemplate();
        $scriptTemplate->setParameters([
            'successCount' => $this->successCount,
            'skipCount' => $this->skipCount,
            'errorCount' => $this->errorCount,
        ]);
        $template->setFile(__DIR__ . '/default.latte');
        $template->render();
    }
}
