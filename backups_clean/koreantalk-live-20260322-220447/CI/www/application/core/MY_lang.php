<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CodeIgniter i18n library by Jérôme Jaglale
// http://maestric.com/en/doc/php/codeigniter_i18n
// version 10 - May 10, 2012

class MY_Lang extends CI_Lang {

	/**************************************************
	 configuration
	***************************************************/

	// languages
  var $languages = array(
    'kr' => 'KOR',            // 한국어
    'bd' => 'BDT',            // 뱅골어
    'jp' => 'JPN',            // 일어
    'ch' => 'CHN',            // 중국어
    'pt' => 'PRT',            // 포르투갈어
    'us' => 'ENG',            // 영어
    'ru' => 'RUS',            // 러시아어
    'hk' => 'HKG',            // 홍콩
    'sg' => 'ENG',            // 싱가폴
    'tw' => 'TWN',            // 대만
    'fr' => 'FRA',            // 프랑스
    'es' => 'ESP',            // 스페인
    'gs' => 'ENG',            // 괌사이판
    'vn' => 'ENG',            // 베트남
    'ca' => 'ENG',            // 캐나다
    'br' => 'PRT',            // 브라질
    'my' => 'ENG',            // 영어
    'mo' => 'HKG',            // 홍콩
);

	// special URIs (not localized)
	var $special = array (
		""
	);

	// where to redirect if no language in URI
	var $default_uri = '';

	var $nationcode;

	/**************************************************/


  function __construct()
  {
      parent::__construct();

      global $CFG;
      global $URI;
      global $RTR;


      $segment = $URI->segment(1);
      if($segment!="shop"){
          $this->nationcode =  $URI->segment(1);
          //echo $_COOKIE['lang'];
          if(isset($_COOKIE['lang']) && strlen($_COOKIE['lang']) == 3){
              $language = $_COOKIE['lang'];
              $CFG->set_item('language', $language);
          }else{

              if (isset($this->languages[$segment]))    // URI with language -> ok
              {

                  $language = $this->languages[$segment];

                  //echo $language;
                  //echo $language;
                  $CFG->set_item('language', $language);
              }
              else if($this->is_special($segment)) // special URI -> no redirect
              {
                  return;
              }
              else    // URI without language -> redirect to default_uri
              {
                  // set default language
                  //echo "dddddddddddd";
                  //echo $this->languages[$this->default_lang()];
                  $CFG->set_item('language', $this->languages[$this->default_lang()]);

                  header("Location: " . $CFG->site_url($this->localized($this->default_uri)), TRUE, 302);
                  exit;
              }

          }
      }

  }

	// get current language
	// ex: return 'en' if language in CI config is 'english'
	function lang()
	{
		global $CFG;
		$language = $CFG->item('language');

		//echo $language;

		$lang = array_search($language, $this->languages);
		if ($lang)
		{
			return $lang;
		}

		return NULL;	// this should not happen
	}

	function is_special($uri)
	{
		$exploded = explode('/', $uri);
		if (in_array($exploded[0], $this->special))
		{
			return TRUE;
		}
		if(isset($this->languages[$uri]))
		{
			return TRUE;
		}
		return FALSE;
	}

	function switch_uri($lang)
	{
		$CI =& get_instance();

		$uri = $CI->uri->uri_string();
		if ($uri != "")
		{
			$exploded = explode('/', $uri);
			if($exploded[0] == $this->lang())
			{
				$exploded[0] = $lang;
			}
			$uri = implode('/',$exploded);
		}
		return $uri;
	}

	// is there a language segment in this $uri?
	function has_language($uri)
	{
		$first_segment = NULL;

		$exploded = explode('/', $uri);
		if(isset($exploded[0]))
		{
			if($exploded[0] != '')
			{
				$first_segment = $exploded[0];
			}
			else if(isset($exploded[1]) && $exploded[1] != '')
			{
				$first_segment = $exploded[1];
			}
		}

		if($first_segment != NULL)
		{
			return isset($this->languages[$first_segment]);
		}

		return FALSE;
	}

	// default language: first element of $this->languages
	function default_lang()
	{
		foreach ($this->languages as $lang => $language)
		{
			return $lang;
		}
	}

	// add language segment to $uri (if appropriate)
	function localized($uri)
	{
		if($this->has_language($uri)
				|| $this->is_special($uri)
				|| preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri))
		{
			// we don't need a language segment because:
			// - there's already one or
			// - it's a special uri (set in $special) or
			// - that's a link to a file
		}
		else
		{
			// 국가 코드로 변경
			//$uri = $this->lang() . '/' . $uri;
			$uri = $this->nationcode . '/' . $uri;
		}

		return $uri;
	}

}

/* End of file */