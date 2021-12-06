<?php
namespace App\Presenters;
use Nette;
use Nette\Application\UI\Form;

//use App\Model\PostFacade;
/* 
 * Bude se starat o záklasní výpis obsahu skladu 
 */

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
   	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
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
	 *	- cat_id
	 *	- sub_ids
	 *	//případně další položky dle potřeby
	 */
	
	/*
	 * základní výpis na stránce budou jen kategorie
	 */	    
	public function renderDefault(): void
	{
		$this->template->post =$this->database
			->table('category')
			->order('cat_name ASC');
	}

}

