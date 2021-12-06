<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
//use App\Model\PostFacade;

final class OrderPresenter extends Nette\Application\UI\Presenter
{
	/**
	 * facade se mi moc nelíbí, je fajn mít to mimo ale nejsem si jistý, 
	 * jestli je to v něčem lepší 
	 * proto jedu v klasice, připojení k databázi atd
	 */
	private Nette\Database\Explorer $database;
	
	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}
	
	/**
	 * ve startupu ověříme přihlášení, později práva jednotlivých uživatelů
	 * @return void
	 */
	public function startup(): void
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}
	/**
	 * vypíše
	 * @return void
	 */
	/*
	 * 
--
-- Struktura tabulky `offer`
--

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
	public function renderDefault(): void
	{
		$var = $this->database
			->table('offer')
			->select('id, category, _name, _desc, _price')
			->order('category DESC');
		if($var) 
		{
		    $this->template->post = $var; 
		}
		else 
		{
		    $this->flashMessage('Nepodařilo se načíst data z databáze', 'error');
		    $this->error($message);
		    $this->template->post = $var;
		}
		
		
	}
	/*
	public function renderEdit($postId): void
	{
		$test = $this->facade
			->getPublicArticles()
			->limit(50);
		//print_r($test);exit;
		$this->template->posts = $test;
	}
	 */
	public function renderEdit(int $postId): void
	{
		//$post = 
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
