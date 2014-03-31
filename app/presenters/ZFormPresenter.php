<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenters;

use Nette;

/**
 * Description of ZFormPresenter
 *
 * @author Alex Pavlunenko <alexp at xpresstek.net>
 */
class ZFormPresenter extends BasePresenter{
   
     /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
    
     protected function createComponentZForm() {
        $form = new Nette\Application\UI\Form;
        $form->addText('name', 'Form name:')
                ->setRequired();

        $form->addSubmit('send', 'Save and publish');
        $form->onSuccess[] = $this->postFormSucceeded;

        return $form;
    }
    
    protected function createComponentPageForm() {
        $form = new Nette\Application\UI\Form;
        $form->addText('position', 'Page Position:')
                ->setRequired()
                ->addRule(Nette\Application\UI\Form::INTEGER, 
                        'Position must be numeric.');

        $form->addSubmit('send', 'Save and publish');
        $form->onSuccess[] = $this->postPageSucceeded;

        return $form;
    }
    
    public function postPageSucceeded($form) {
        
        $values = $form->getValues();
        $id = $this->getParameter('id');
        $values->zformid = $id;
        $form = $this->database->table('zformpage')->insert($values);
        

        $this->flashMessage("Page was published", 'success');
        $this->redirect('show', $form->id);
    }
    
     public function postFormSucceeded($form) {
        
        $values = $form->getValues();
        $id = $this->getParameter('id');

        if ($id) {
            $form = $this->database->table('zform')->get($id);
            $form->update($values);
        } else {
            $form = $this->database->table('zform')->insert($values);
        }

        $this->flashMessage("Form was published", 'success');
        $this->redirect('show', $form->id);
    }
    
    public function renderShow($id) {
        $form = $this->database->table('zform')->get($id);

        if (!$form) {
            $this->error('Form not found');
        }

        $this->template->zform = $form;    
        $this->template->pages = $form->related('zformpage')->order('position');
    }
    
    public function actionEdit($id) {

        $form = $this->database->table('zform')->get($id);
        if (!$form) {
            $this->error('Form not found');
        }
        $this['zForm']->setDefaults($form->toArray());
    }
}
