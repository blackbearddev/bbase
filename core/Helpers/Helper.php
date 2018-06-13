<?php 
	namespace Bbase\Helpers;

	use \Bbase\Model;

	class Helper extends Model{

		public static function getRangeDate($type='Y-m-d', $range="month", $n=3, $back=0)
		{
			$dates = array();

			for($i=$back; $i-$back < $n; $i++):
				array_push($dates, date("{$type}", strtotime("-{$i} {$range}")));
			endfor;	
			return $dates;
		}

		public static function BRDate($date)
		{
			return Date("d/m/Y", strtotime($date));
		}

		public static function DBDate($date)
		{
			return Date("Y-m-d", strtotime($date));
		}

		public static function slugStr($string, $opt=1)
		{	
			$formated = Helper::removeAccents($string, $opt);
			return preg_replace('!\s+!', "-", $formated);
		}

		public static function removeAccents($string, $style=1)
		{
			$str = trim($string);
			$str = str_replace('á', 'a', $str);	
			$str = str_replace('à', 'a', $str);	
			$str = str_replace('â', 'a', $str);	
			$str = str_replace('ã', 'a', $str);	

			$str = str_replace('Á', 'a', $str);	
			$str = str_replace('À', 'a', $str);	
			$str = str_replace('Â', 'a', $str);	
			$str = str_replace('Ã', 'a', $str);	


			$str = str_replace('é', 'e', $str);	
			$str = str_replace('è', 'e', $str);	
			$str = str_replace('ê', 'e', $str);

			$str = str_replace('É', 'e', $str);	
			$str = str_replace('È', 'e', $str);	
			$str = str_replace('Ê', 'e', $str);	

			$str = str_replace('í', 'a', $str);	
			$str = str_replace('ì', 'a', $str);	
			$str = str_replace('î', 'a', $str);	

			$str = str_replace('Í', 'i', $str);	
			$str = str_replace('Ì', 'i', $str);	
			$str = str_replace('Î', 'i', $str);	
		
			$str = str_replace('ó', 'o', $str);	
			$str = str_replace('ò', 'o', $str);	
			$str = str_replace('ô', 'o', $str);	
			$str = str_replace('õ', 'o', $str);	

			$str = str_replace('Ó', 'o', $str);	
			$str = str_replace('Ò', 'o', $str);	
			$str = str_replace('Ô', 'o', $str);	
			$str = str_replace('Õ', 'o', $str);	

			$str = str_replace('ú', 'u', $str);	
			$str = str_replace('ù', 'u', $str);	
			$str = str_replace('û', 'u', $str);	

			$str = str_replace('Ú', 'u', $str);	
			$str = str_replace('Ù', 'u', $str);	
			$str = str_replace('Û', 'u', $str);	

			$str = str_replace('ç', 'c', $str);	
			$str = str_replace('Ç', 'c', $str);	

			if($style==1):
				$str = strtolower($str);
			else:
				$str = strtoupper($str);
			endif;
			return $str;
		}

		public static function getRoot($path="")
		{
			return __DIR__ . $path;
		}

		public static function getThis()
		{
			return 1;
		}


	}