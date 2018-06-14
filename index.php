<?php
	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once ("vendor/autoload.php");


	use Bbase\Model\User;
	use Bbase\DB\Sql;

	 /*["mtnome"]=> string(6) "asdasd" ["mtlogin"]=> string(6) "asdasd" ["nrphone"]=> string(6) "asdasd" ["mtemail"]=> string(17) "asdas@sdad.com.br" ["mtpassword"]=> string(7) "sfsddfs" ["mtisadmin"]=> string(1) "1"

	 $dados = array(
	 		"iduser" => 8,
	 		"mtperson" => "carlos mateus",
	 		"mtlogin" => "carlos",
	 		"mtpassword" => "carlos",
	 		"nrphone" => "1121221",
	 		"mtemail" => "carlos@carvaljos.com.br",
	 		"mtisadmin" => "1"
	 );


	 $user = new User();
	 $user->setData($dados);
	 //var_dump($user->getValues());

	 //$user->delete();
	/*
	 $user->save();
	 $res = User::listAll();
	 var_dump($res);*/



