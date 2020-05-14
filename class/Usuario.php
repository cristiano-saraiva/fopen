<?php

class Usuario
{
    private $id;
    private $deslogin;
    private $dessenha;
    private $dtcadastro;


    public function __construct($id = "", $login = "", $password = "")
    {
        $this->setId($id);
        $this->setDeslogin($login);
        $this->setDessenha($password);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDeslogin()
    {
        return $this->deslogin;
    }

    public function getDessenha()
    {
        return $this->dessenha;
    }

    public function getDtcadastro()
    {
        return $this->dtcadastro;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDeslogin($deslogin)
    {
        $this->deslogin = $deslogin;
    }

    public function setDessenha($dessenha)
    {
        $this->dessenha = $dessenha;
    }

    public function setDtcadastro($dtcadastro)
    {
        $this->dtcadastro = $dtcadastro;
    }

    public function loadById($id)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_usuarios WHERE id = :ID", array(
            ":ID" => $id
        ));

        if (count($results) > 0) {
            $row = $results[0];

            $this->setData($results[0]);
        }
    }

    public function update($id, $login, $password)
    {
        $this->setId($id);
        $this->setDeslogin($login);
        $this->setDessenha($password);

        $sql = new Sql();

        $sql->query("UPDATE tb_usuarios SET deslogin = :LOGIN, dessenha = :PASSWORD WHERE id = :ID", array(
            ':LoGIN'    => $this->getDeslogin(),
            ':PASSWORD' => $this->getDessenha,
            ':ID'       => $this->getId
        ));
    }

    public static function getList()
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_usuarios ORDER BY deslogin;");
    }

    public static function search($login)
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_usuarios WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(
            ":SEARCH" => "%" . $login . "%"
        ));
    }
    public function login($login, $password)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_usuarios WHERE deslogin = :LOGIN AND dessenha = :PASSWORD", array(
            ":LOGIN" => $login,
            ":PASSWORD" => $password
        ));

        if (count($results) > 0) {
            $row = $results[0];

            $this->setData($results[0]);
        } else {
            throw new Exception("Login e/ou senha inválidos!");
        }
    }

    public function setData($data)
    {
        $this->setId($data['id']);
        $this->setDeslogin($data['deslogin']);
        $this->setDessenha($data['dessenha']);
        $this->setDtcadastro(new DateTime($data['dtcadastro']));
    }

    public function insert()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(
            ':LOGIN'    => $this->getDeslogin(),
            ':PASSWORD' => $this->getDessenha()
        ));

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }
     public function delete()
    {
        $sql = new Sql();

        $results = $sql->select("DELETE FROM tb_usuarios WHERE id = :ID", array(
            ':ID'    => $this->getId()
        ));

       $this->setId(0);
       $this->setDeslogin("");
       $this->setDessenha("");
       $this->setDtcadastro(new DateTime());
    }

    public function __toString()
    {
        return json_encode(array(
            "id" => $this->getId(),
            "deslogin" => $this->getDeslogin(),
            "dessenha" => $this->getDessenha(),
            "dtcadastro" => $this->getDtcadastro()->format("d/m/Y H:i:s")
        ));
    }
}
