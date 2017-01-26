<?php
function friendly_url($nadpis) {

	setlocale(LC_CTYPE, 'cs_CZ');
    setlocale(LC_ALL, 'cs_CZ');
    setlocale(LC_COLLATE, 'cs_CZ.utf8');

    $url = $nadpis;
    //$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("UTF-8", "ASCII//TRANSLIT", $url);
    $url = strtolower($url);
    //$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}

function slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

  // trim
  $text = trim($text, '-');

  // transliterate
  if (function_exists('iconv'))
  {
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  }

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('#[^-\w]+#', '', $text);

  if (empty($text))
  {
    return 'n-a';
  }

  return $text;
}

function friendly_url_old_way($nadpis){

	$url = $nadpis;
	$url = trim($url, "-");
	$url = strtolower($url);

	$prevodni_tabulka = Array(
		  'ä'=>'a',
		  'Ä'=>'A',
		  'á'=>'a',
		  'Á'=>'A',
		  'à'=>'a',
		  'À'=>'A',
		  'ã'=>'a',
		  'Ã'=>'A',
		  'â'=>'a',
		  'Â'=>'A',
		  'č'=>'c',
		  'Č'=>'C',
		  'ć'=>'c',
		  'Ć'=>'C',
		  'ď'=>'d',
		  'Ď'=>'D',
		  'ě'=>'e',
		  'Ě'=>'E',
		  'é'=>'e',
		  'É'=>'E',
		  'ë'=>'e',
		  'Ë'=>'E',
		  'è'=>'e',
		  'È'=>'E',
		  'ê'=>'e',
		  'Ê'=>'E',
		  'í'=>'i',
		  'Í'=>'I',
		  'ï'=>'i',
		  'Ï'=>'I',
		  'ì'=>'i',
		  'Ì'=>'I',
		  'î'=>'i',
		  'Î'=>'I',
		  'ľ'=>'l',
		  'Ľ'=>'L',
		  'ĺ'=>'l',
		  'Ĺ'=>'L',
		  'ń'=>'n',
		  'Ń'=>'N',
		  'ň'=>'n',
		  'Ň'=>'N',
		  'ñ'=>'n',
		  'Ñ'=>'N',
		  'ó'=>'o',
		  'Ó'=>'O',
		  'ö'=>'o',
		  'Ö'=>'O',
		  'ô'=>'o',
		  'Ô'=>'O',
		  'ò'=>'o',
		  'Ò'=>'O',
		  'õ'=>'o',
		  'Õ'=>'O',
		  'ő'=>'o',
		  'Ő'=>'O',
		  'ř'=>'r',
		  'Ř'=>'R',
		  'ŕ'=>'r',
		  'Ŕ'=>'R',
		  'š'=>'s',
		  'Š'=>'S',
		  'ś'=>'s',
		  'Ś'=>'S',
		  'ť'=>'t',
		  'Ť'=>'T',
		  'ú'=>'u',
		  'Ú'=>'U',
		  'ů'=>'u',
		  'Ů'=>'U',
		  'ü'=>'u',
		  'Ü'=>'U',
		  'ù'=>'u',
		  'Ù'=>'U',
		  'ũ'=>'u',
		  'Ũ'=>'U',
		  'û'=>'u',
		  'Û'=>'U',
		  'ý'=>'y',
		  'Ý'=>'Y',
		  'ž'=>'z',
		  'Ž'=>'Z',
		  'ź'=>'z',
		  'Ź'=>'Z'
		);
	$url = strtr($url, $prevodni_tabulka);
die($url);
	return $url;

}
?>