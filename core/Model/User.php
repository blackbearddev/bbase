<?php
namespace Bbase\Model;

use \Bbase\DB\Sql;
use \Bbase\DB\Pg;
use \Bbase\Model;
use \Bbase\Mailer;

class User extends Model
{
    const SESSION = "User";
    const SECRET = SECRET;

    public static function login($login, $password)
    {
        $sql = new Sql();
        $results = $sql->select("select * from mt_users where mtlogin= :LOGIN", array(
            ":LOGIN" => $login
        ));

        if(count($results) === 0)
        {
            throw  new \Exception("Usuario inexistente ou senha inválida.");
        }

        $data = $results[0];
        if(password_verify($password, $data['mtpassword'])):
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
            !(int)$_SESSION[User::SESSION]['iduser'] > 0
            ||
            (bool)$_SESSION[User::SESSION]['mtisadmin'] !== $isadmin
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

    public static function getForgot($email, $isadmin=true)
    {
        $sql = new Sql();
        $results = $sql->select("select *
        from mt_persons a
        inner join mt_users b using(idperson)
        where a.mtemail = :email", array(
            ':email' => $email
        ));

        if(count($results)===0):
            throw new \Exception("Não foi encontrado esse e-mail");
        else:
            $data = $results[0];
            $result2 = $sql->select("call sp_userspasswordsrecoveries_create(:iduser, :mtip)", array(
                ":iduser" => $data['iduser'],
                ":mtip" => $_SERVER['REMOTE_ADDR']
            ));

            if(count($result2)===0):
                throw new \Exception("Não foi encontrado esse e-mail"); 
            else:
                $dataRecovery = $result2[0];
                $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));

                $code = openssl_encrypt($dataRecovery['idrecovery'], 'aes-256-cbc', User::SECRET,0, $iv);

                $result = base64_encode($iv.$code);
                
                if($isadmin):
                $link = "http://www.blackbeard.com.br/admin/forgot/reset?code=$result";
                else:
                $link = "http://www.blackbeard.com.br/forgot/reset?code=$result";
                endif;
                $mailer = new Mailer($data['mtemail'], $data['mtperson'], "Redefinir Senha da Blackbeard", "forgot", array(
                        "name" => $data['mtperson'],
                        "link" => $link
                    ));
                $mailer->send();
                return $link;
            endif;

        endif;

    }

    public  static function validForgotDecrypt($result)
    {
       $result = base64_decode($result);
       $code = mb_substr($result, openssl_cipher_iv_length('aes-256-cbc'), null, '8bit');
       $iv = mb_substr($result, 0, openssl_cipher_iv_length('aes-256-cbc'), '8bit');;
       $idrecovery = openssl_decrypt($code, 'aes-256-cbc', User::SECRET, 0, $iv);
       $sql = new Sql();
       $results = $sql->select("
         select *
         from mt_userspasswordsrecoveries a
         INNER JOIN mt_users b USING(iduser)
         INNER JOIN mt_persons c USING(idperson)
         WHERE
         a.idrecovery = :idrecovery
         AND
         a.dtrecovery IS NULL
         AND
         DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
     ", array(
         ":idrecovery"=>$idrecovery
     ));

       if(count($results)===0):
        throw new \Exception("Error Processing Request");
       else:
        return $results[0];
       endif;


    }

    public static function setForgotUsed($idrecovery)
    {
        $sql = new Sql();
        $sql->query("update mt_userspasswordsrecoveries set dtrecovery=NOW() where idrecovery=:idrecovery", array(":idrecovery" => $idrecovery));
    }

    public function setPassword($password)
    {
        $sql = new Sql();
        $sql->query("update mt_users set mtpassword = :password where iduser= :iduser", array(
            ":password" => $password,
            ":iduser" => $this->getiduser()
        ));
    }
}
