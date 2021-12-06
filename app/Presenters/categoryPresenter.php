<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class CategoryPresenter extends Nette\Application\UI\Presenter
	/* napojení na databázi */{
    
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
	/*
	 * Máme 3 tabulky:
	 *  - category
	 *	- id
	 *	- cat_name
	 *  - sub_category
	 *	- id
	 *	- sub_name
	 *  - item
	 *	- id
	 *	- name
	 *	- cat_id // toto je špatně :( mělo by to být v další tabulce jako vztah mezi těmito třemi tabulkami :( nutno vymyslet
	 *	- sub_ids // toto je špatně :( mělo by to být v další tabulce jako vztah mezi těmito třemi tabulkami :( nutno vymyslet
	 *	//případně další položky dle potřeby
	 * 
	 *  - category_item_sub_category // každý řádek tabulky je vzdah mezi jednotlivými položkami.
	 *	- cat_name
	 *	- sub_name
	 *	- item
	 */

	/* získání dat z databáze */
	public function renderShow(int $postId): void
	{
		$kategoryName = $this->database
			->table('category')
			->where('id', $postId) ;
		$this->template->post = $this->database
			->table('item')
			->where('cat_id', $postId)
			->order('cat_name ASC');
		$this->template->post['cat_name'] = $kategoryName;
	}
	
	
	public function functionName($param) {
	    
	}
}
