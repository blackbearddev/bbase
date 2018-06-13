<?php 
	namespace Bbase\DB;

	use \Bbase\DB\Sql;

	class Pg extends Sql
	{
		const DSN = 'pgsql:host=localhost port=5432 dbname=medtra user=sistema password=soeusei';

		function __construct()
		{
			parent::__construct(Pg::DSN);
		}


	}