<?php
namespace Bbase\Model;

use \Bbase\DB\Sql;
use \Bbase\DB\Pg;
use \Bbase\Model;

class User extends Model
{
    const SESSION = "User";

    public static function login($login, $password)
    {
        $sql = new Sql();
        $results = $sql->select("select * from users where login= :LOGIN", array(
            ":LOGIN" => $login
        ));

        if(count($results) === 0)
        {
            throw  new \Exception("Usuario inexistente ou senha inválida.");
        }

        $data = $results[0];
        if(password_verify($password, $data['password'])):
            $user = new User();
            $user->setData($data);
            $_SESSION[User::SESSION] = $user->getValues();
           return $user;
        else:
            throw  new \Exception("Usuario inexistente ou senha inválida.");
        endif;
    }

    public static function verifyLogin($isadmin=true)
    {
        if(
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]['id'] > 0
            ||
            (bool)$_SESSION[User::SESSION]['isadmin'] !== $isadmin
            ):
            header("Location: /admin/login");
            exit;
        endif;
    }

    public static function logout()
    {
        $_SESSION[User::SESSION]= NULL;
    }

    public static function listAll()
    {
        $sql = new Sql();
        return $sql->select("select * from mt_users a inner join mt_persons b using(idperson) order by b.mtperson");
    }

    public function save()
    {
        $sql = new Sql();
        $results = $sql->select("CALL sp_users_save(:mtperson, :mtlogin, :mtpassword, :mtemail, :nrphone, :mtisadmin)", array(
               ":mtperson" => $this->getmtperson(),
               ":mtlogin" => $this->getmtlogin(),
               ":mtpassword" => $this->getmtpassword(),
               ":mtemail" => $this->getmtemail(),
               ":nrphone" => $this->getnrphone(),
               ":mtisadmin" =>  $this->getmtisadmin()
        ));

        $this->setData($results[0]);

    }

    public function get($iduser)
    {
        $sql = new Sql();
        $results = $sql->select("select * from  mt_users a inner join mt_persons b using(idperson) where a.iduser = :iduser", array(
                ":iduser"=> $iduser
        ));

        $this->setData($results[0]);
    }

    public function update()
    {
         $sql = new Sql();
        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :mtperson, :mtlogin, :mtpassword, :mtemail, :nrphone, :mtisadmin)", array(
               ":iduser" => $this->getiduser(),
               ":mtperson" => $this->getmtperson(),
               ":mtlogin" => $this->getmtlogin(),
               ":mtpassword" => $this->getmtpassword(),
               ":mtemail" => $this->getmtemail(),
               ":nrphone" => $this->getnrphone(),
               ":mtisadmin" =>  $this->getmtisadmin()
        ));

        $this->setData($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();
        $sql->query("CALL sp_users_delete(:iduser)", array(
            ":iduser" => $this->getiduser()
        ));
    }
}
