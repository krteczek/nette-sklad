<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

declare(strict_types=1);
namespace App\Presenters;
use Nette\Security as NS;

class Authenticator implements NS\IAuthenticator
{
	private $database;

	private $passwords;

	public function __construct(Nette\Database\Context $database, NS\Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}

	public function authenticate(array $credentials): NS\Identity
	{
		[$username, $password] = $credentials;

		$row = $this->database->table('users')
			->where('username', $username)->fetch();

		if (!$row) {
			throw new NS\AuthenticationException('User not found.');
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new NS\AuthenticationException('Invalid password.');
		}

		return new NS\Identity($row->id, $row->role, ['username' => $row->username]);
	}
}
