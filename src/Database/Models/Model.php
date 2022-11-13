<?php

namespace Obsidian\Database\Models;

use Obsidian\Database\Connection;

class Model extends Connection
{
    /**
     * @var mixed table
     */
    protected $table;

    /**
     * @var mixed db
     */
    private static $db;

    /**
     * findAll.
     *
     * @return void
     */
    public function findAll()
    {
        $query = $this->request('SELECT * FROM '.$this->table);

        return $query->fetchAll();
    }

    /**
     * findBy.
     *
     * @param array criteres
     *
     * @return void
     */
    public function findBy(array $criteres)
    {
        $champs = [];
        $valeurs = [];

        foreach ($criteres as $champ => $valeur) {
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        $liste_champs = implode(' AND ', $champs);

        return $this->request('SELECT * FROM '.$this->table.' WHERE '.$liste_champs, $valeurs)->fetchAll();
    }

    /**
     * find.
     *
     * @param int id
     *
     * @return void
     */
    public function find(int $id)
    {
        return $this->request("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }

    /**
     * create.
     *
     * @return void
     */
    public function create()
    {
        $champs = [];
        $inter = [];
        $valeurs = [];

        foreach ($this as $champ => $valeur) {
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = $champ;
                $inter[] = '?';
                $valeurs[] = $valeur;
            }
        }

        $liste_champs = implode(', ', $champs);
        $liste_inter = implode(', ', $inter);

        return $this->request('INSERT INTO '.$this->table.' ('.$liste_champs.')VALUES('.$liste_inter.')', $valeurs);
    }

    /**
     * update.
     *
     * @return void
     */
    public function update()
    {
        $champs = [];
        $valeurs = [];

        foreach ($this as $champ => $valeur) {
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }
        $valeurs[] = $this->id;

        $liste_champs = implode(', ', $champs);

        return $this->request('UPDATE '.$this->table.' SET '.$liste_champs.' WHERE id = ?', $valeurs);
    }

    /**
     * delete.
     *
     * @param int id
     *
     * @return void
     */
    public function delete(int $id)
    {
        return $this->request("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * request.
     *
     * @param string sql
     * @param array attributs
     *
     * @return void
     */
    public function request(string $sql, array $attributs = null)
    {
        self::$db = Connection::getInstance();

        if ($attributs !== null) {
            $query = $this->db->prepare($sql);
            $query->execute($attributs);

            return $query;
        } else {
            return self::$db->query($sql);
        }
    }

    /**
     * hydrate.
     *
     * @param mixed donnees
     *
     * @return void
     */
    public function hydrate($donnees)
    {
        foreach ($donnees as $key => $value) {
            $setter = 'set'.ucfirst($key);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        return $this;
    }
}
