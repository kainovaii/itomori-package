<?php

namespace App\src\Models;

use Obsidian\Core\Model;

class UsersModel extends Model
{
    protected $id;
    protected $email;
    protected $password;
    protected $roles;

    public function __construct()
    {
        $class = str_replace(__NAMESPACE__.'\\', '', __CLASS__);
        $this->table = strtolower(str_replace('Model', '', $class));
    }

    public function findOneByUsername(string $username)
    {
        return $this->request("SELECT * FROM {$this->table} WHERE username = ?", [$username])->fetch();
    }

    public function setSession()
    {
        $_SESSION['USER'] = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'roles' => $this->roles,
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles($roles)
    {
        $this->roles = json_decode($roles);

        return $this;
    }
}
