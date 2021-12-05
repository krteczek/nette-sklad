<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter
	/* napojení na databázi */{
    
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	/* získání dat z databáze */
	public function renderShow(int $postId): void
	{
	    /*
	     * 
	     
		$this->template->post = $this->database
			->table('posts')
			->get($postId);
	     * 
	     */
	    	$post = $this->database
			->table('posts')
			->get($postId);
		if (!$post) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->post = $post;
		$this->template->comments = $post->related('comments')->order('created_at');

	}
	
	/* formulář na komentování pod články */ 
	protected function createComponentCommentForm(): Form
	{
		$form = new Form; // means Nette\Application\UI\Form
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];

		$form->addText('name', 'Jméno:')
			->setRequired();

		$form->addEmail('email', 'E-mail:');

		$form->addTextArea('content', 'Komentář:')
			->setRequired();

		$form->addSubmit('send', 'Publikovat komentář');

		return $form;
	}
	/* uložení komentáře do db */
	public function commentFormSucceeded(\stdClass $values): void
	{
		$postId = $this->getParameter('postId');

		$this->database->table('comments')->insert([
			'post_id' => $postId,
			'name' => $values->name,
			'email' => $values->email,
			'content' => $values->content,
		]);

		$this->flashMessage('Děkuji za komentář', 'success');
		$this->redirect('this');
	}

}
