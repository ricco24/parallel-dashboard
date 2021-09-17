<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Sidebar\Sidebar;
use App\Models\PageScripts;
use Nette\Application\UI\Presenter;
use Nette\Database\Explorer;

class BasePresenter extends Presenter
{
    protected Explorer $db;

    protected PageScripts $pageScripts;

    public function __construct(Explorer $db, PageScripts $pageScripts)
    {
        parent::__construct();
        $this->db = $db;
        $this->pageScripts = $pageScripts;
    }

    public function startup()
    {
        parent::startup();
        $this->template->pageScripts = $this->pageScripts;
    }

    protected function createComponentSidebar(): Sidebar
    {
        return new Sidebar($this->db);
    }
}
