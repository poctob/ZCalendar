<?php

namespace App\Presenters;

use Nette,
    App\Model,
    App\ZFormBuilder\FormBuilder,
    App\ZFormBuilder\Components\PickList;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    /** @var Nette\Database\Context */
    private $database;
    private $formBuilder;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
        $this->formBuilder = new FormBuilder();
    }

    public function renderDefault() {
        $this->template->zforms = $this->database->table('zform')
                ->order('created_at DESC');
    }
    
    protected function createComponentBuilderForm() {
        return $this->formBuilder->createComponentBuilderForm();
    }
    
    protected function createComponentPickList()
    {
        return new PickList($this->formBuilder->getAvailableComponents(),
                "Available Components",
                "Selected Components");
    }

}
