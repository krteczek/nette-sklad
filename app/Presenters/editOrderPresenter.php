<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\PostFacade;

final class EditOrderPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}
	public function startup(): void
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}
	
	protected function createComponentPostForm(): Form
	{
/**
 *		Struktura tabulek, abychom věděli, co potřebjeme získat
 *		CREATE TABLE `categories` (
		   `id` int(11) NOT NULL,
		    `cat_name` varchar(30) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 * 
		CREATE TABLE `offer` (
		  `id` int(11) NOT NULL,
		  `create_time` datetime DEFAULT current_timestamp(),
		  `category` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `_desc` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `_price` double DEFAULT NULL,
		  `image` longblob NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		
 */
	    
		// zízkáme nejprve hodnoty kategorii
		$var = $this->database
				->table('categories')
				->select('id, cat_name')
				->order('cat_name DESC');
		if (!$var) {
		    $this->flashMessage('Nejsou vytvořené žádné kategorie, do kterých bychom mohli zaředit přidávané zboží', 'error');
		    $this->redirect('Order:createNewCategory');
		}
		
		$form = new Form;
		$form->addRadioList('category', 'Kategorie jídla:', $var)
			->setRequired();
		$form->addText('_name', 'Název jídla:')
			->setRequired();
		$form->addText('_desc', 'Popis jídla')
			->setRequired();
		$form->addText('_price', 'Název jídla:')
			->setRequired();

		$form->addSubmit('send', 'Uložit a publikovat');
		$form->onSuccess[] = [$this, 'postFormSucceeded'];

		return $form;
	}

	public function postFormSucceeded(array $values): void
	{
		$postId = $this->getParameter('postId');

		if ($postId) {
			$post = $this->database
				->table('posts')
				->get($postId);
			$post->update($values);

		} else {
			$post = $this->database
				->table('posts')
				->insert($values);
		}

		$this->flashMessage('Příspěvek byl úspěšně publikován.', 'success');
		$this->redirect('Post:show', $post->id);
	}

	public function renderEdit(int $postId): void
	{
		$post = $this->database
			->table('posts')
			->get($postId);

		if (!$post) {
			$this->error('Post not found');
		}

		$this->getComponent('postForm')
			->setDefaults($post->toArray());
	}

}
