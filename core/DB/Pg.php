<?php 
	namespace Bbase\DB;

	use \Bbase\DB\Sql;


	class Pg extends Sql
	{
		const HOSTNAME = HOSTNAME;
    	const USERNAME = USERNAME;
    	const PASSWORD = PASSWORD;
    	const DBNAME = DBNAME;

		const DSN = 'pgsql:host=' . Pg::HOSTNAME .'port=5432 dbname=' . Pg::DBNAME.' user='. Pg::USERNAME .' password=soeusei';

		function __construct()
		{
			parent::__construct(Pg::DSN);
		}


	}