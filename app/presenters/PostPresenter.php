<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostPresenter
 *
 * @author Alex Pavlunenko <alexp at xpresstek.net>
 */

namespace App\Presenters;

use Nette;

class PostPresenter extends BasePresenter {

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function renderShow($postId) {
        $post = $this->database->table('posts')->get($postId);

        if (!$post) {
            $this->error('Post not found');
        }

        $this->template->post = $post;
        $this->template->comments = $post->related('comments')->order('created_at');
    }

    protected function createComponentCommentForm() {
        $form = new Nette\Application\UI\Form;

        $form->addText('name', 'Your name:')
                ->setRequired();

        $form->addText('email', 'Email:');

        $form->addTextArea('content', 'Comment:')
                ->setRequired();

        $form->addSubmit('send', 'Publish comment');

        $form->onSuccess[] = $this->commentFormSucceeded;

        return $form;
    }

    public function commentFormSucceeded($form) {
        $values = $form->getValues();
        $postId = $this->getParameter('postId');

        $this->database->table('comments')->insert(array(
            'post_id' => $postId,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ));

        $this->flashMessage('Thank you for your comment', 'success');
        $this->redirect('this');
    }

    protected function createComponentPostForm() {
        $form = new Nette\Application\UI\Form;
        $form->addText('title', 'Title:')
                ->setRequired();
        $form->addTextArea('content', 'Content:')
                ->setRequired();

        $form->addSubmit('send', 'Save and publish');
        $form->onSuccess[] = $this->postFormSucceeded;

        return $form;
    }

    public function postFormSucceeded($form) {
        
        if (!$this->user->isLoggedIn()) {
            $this->error('You need to log in to create or edit posts');
        }
        
        $values = $form->getValues();
        $postId = $this->getParameter('postId');

        if ($postId) {
            $post = $this->database->table('posts')->get($postId);
            $post->update($values);
        } else {
            $post = $this->database->table('posts')->insert($values);
        }

        $this->flashMessage("Post was published", 'success');
        $this->redirect('show', $post->id);
    }

    public function actionEdit($postId) {
        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $post = $this->database->table('posts')->get($postId);
        if (!$post) {
            $this->error('Post not found');
        }
        $this['postForm']->setDefaults($post->toArray());
    }

    public function actionCreate() {
        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

}
