<?php
// パラメータ受け入れクラス PuzzleBall
// Copyright 2015 ytocky

require_once "BallException.php";

class PuzzleBall
{
	/*  ルールは、以下の種類を提供。
		PATTERN		// パターン
		INTEGER		// デフォルトは3桁。
		ALPHABET	// デフォルトは3文字
		NUMBER		// 数字。ピリオド、マイナス含む。
		ALPHANUM	// 数字とアルファベット
		WHITELIST	// 期待値を列挙した、ホワイトリスト形式
		UTF8		// UTF8(改行なし)
		UTF8TEXT	// UTF8(改行を許容)
	*/
	const PATTERN	= 'Pattern';
	const INTEGER	= 'Integer';
	const ALPHABET	= 'Alphabet';
	const NUMBER	= 'Number';
	const ALPHABET_NUMBER = 'AlphaNum';
	const ALPHANUM	= 'AlphaNum';
	const WHITELIST	= 'WhiteList';
	const UTF8		= 'UTF8';
	const UTF8TEXT	= 'UTF8Text';

	// for conf-behavior
	const EXCEPTION = 100;
	const ONGOING = 101;

	private $values = array();
	private $cookies = array();

	private $BAK_GET = array();
	private $BAK_POST = array();
	private $BAK_ENV = array();
	private $BAK_COOKIE = array();

	private $conf;
	private $defaultConf = array(
		'behavior' => '',
		'cli_post_max' => 10240,
	);

	public function __construct($rules, $conf = NULL)
	{
		$this->conf = is_null($conf) ? $this->defaultConf : array_merge($this->defaultConf, $conf);

		// for CLI
		if (count($_GET) == 0)
		{
			$this->getGET();
		}
		if (count($_POST) == 0)
		{
			$this->getPOST();
		}

		if (count($_GET)>0)
		{
			$this->BAK_GET = $_GET;
		}
		if (count($_POST)>0)
		{
			$this->BAK_POST = $_POST;
		}
		if (count($_ENV)>0)
		{
			$this->BAK_ENV = $_ENV;
		}
		if (count($_COOKIE)>0)
		{
			$this->BAK_COOKIE = $_COOKIE;
		}

		$this->add($rules);

		/* all inputs are clear */
		$_GET = NULL;
		$_POST = NULL;
		$_ENV = NULL;
		$_COOKIE = NULL;

	} // end of function __construct()

	private function getGET()
	{
		if (isset($_ENV['QUERY_STRING']))
		{
			parse_str($_ENV['QUERY_STRING'], $_GET);
		}
	}


	private function getPOST()
	{
		if (defined('STDIN') && $_ENV['CONTENT_LENGTH'])
		{
			$maxlength = min($this->conf['cli_post_max'], $_ENV['CONTENT_LENGTH']);
			$data = fread(STDIN, $this->conf['cli_post_max']);
			parse_str($data, $_POST);
		}
	}

	public function add($rules)
	{
		if (isset($rules['GET'])) {
			$this->check($this->BAK_GET, $rules['GET'], $this->values);
		} // end of if (isset($rules['GET']))

		if (isset($rules['POST'])) {
			$this->check($this->BAK_POST, $rules['POST'], $this->values);
		} // end of if (isset($rules['POST']))

		if (isset($rules['ENV'])) {
			$this->check($this->BAK_ENV, $rules['ENV'], $this->values);
		} // end of if (isset($rules['ENV']))

		if (isset($rules['COOKIE'])) {
			$this->check($this->BAK_COOKIE, $rules['COOKIE'], $this->cookies);
		} // end of if (isset($rules['COOKIE']))
	}


	private function check($params, $rules, &$storage)
	{

		foreach ($rules as $key => $rule)
		{
			if (preg_match('/(.+)\[\]$/', $key, $matches))
			{
				// 配列を受け取る

				$key = $matches[1];
				$list = isset($params[$key]) ? $params[$key] : array();

				$this->checkArray($key, $list, $rule, $storage);

			} else {
				$defaultRule = array(
					'type' => self::PATTERN,
					'pattern' => '/^\d{1,10}$/',
				);
				$rule = array_merge($defaultRule, $rule);

				if ( !isset($params[$key]) )
				{
					// 期待したパラメータが含まれないときは、無視して続行
					continue;
				}
				$value = $params[$key];

				$this->checkValue($key, $value, $rule, $storage);

			}
		} // end of foreach ($rules)

	} // end of function check()


	// 'key' and 'value'
	private function checkArrayKeyAndValue($key, $index, $value, $rules, &$storage)
	{
		// index check
		$result = $this->checkone($index, $rules['key']);

		if ($result)
		{
			$result = $this->checkone($value, $rules['value']);
			if ($result)
			{
				$storage[$key][$index] = $value;
			}
			else
			{
				$this->assignFalseValue( $storage[$key][$index], $rules['value']);
			}
		}
	}


	// 'keyvalue'
	private function checkArrayKV($key, $index, $value, $rules, &$storage)
	{
		$rule = $rules['keyvalue'];
		if ( isset($rule[$index]) )
		{
			// 受け取るindex
			$result = $this->checkone($value, $rule[$index]);

			if ($result)
			{
				$storage[$key][$index] = $value;
			} else {
				$this->assignFalseValue( $storage[$key][$index], $rule);
			}

		} else {
			// 受け取らないindex
			// NOP
		}
	}


	private function checkArray($key, $params, $rule, &$storage)
	{
		// $value == array
		foreach ($params as $index => $v)
		{
			if ( isset($rule['key']) && isset($rule['value']) )
			{
				$this->checkArrayKeyAndValue($key, $index, $v, $rule, $storage);
			}
			else if ( isset($rule['keyvalue']) )
			{
				$this->checkArrayKV($key, $index, $v, $rule, $storage);
			}
		}
	}


	private function checkValue($key, $value, $rule, &$storage)
	{
		$result = $this->checkone($value, $rule);

		if ($result)
		{
			$storage[$key] = $value;
		} else {
			$this->assignFalseValue( $storage[$key], $rule);
		}
	}


	private function assignFalseValue( &$target, $rule )
	{
		if (isset($this->conf['behavior']))
		{
			switch ($this->conf['behavior'])
			{
				case self::ONGOING:
					// パラメータが渡されていないものとして、処理を続行。
					$target = null;
					return;
			}
		}

		if ( isset($rule['fallback']) )
		{
			$target = $rule['fallback'];
		} else {
			throw new PuzzleBall\BallException();
		}

	}


	protected function checkone($value, $rule)
	{
		$func = "check_" . $rule['type'];

		$result = $this->$func($value, $rule);

		return $result;
	}


	protected function checkRange($value, $range)
	{
		$min = $range['min'];
		$max = $range['max'];
		if ($min <= $value && $value <= $max)
		{
			return true;
		}
		return false;
	}  // end of function checkRange()


	protected function checkLength($value, $length)
	{
		$min = $length['min'];
		$max = $length['max'];
		$length = mb_strlen($value, 'UTF-8');
		if ($min <= $length && $length <= $max)
		{
			return true;
		}
		return false;
	}  // end of function checkLength()


	private function check_Pattern($value, $rule)
	{
		return preg_match($rule['pattern'], $value);
	} // end of function check_Pattern()


	private function check_Integer($value, $rule)
	{

		if ( $value === "" || !ctype_digit($value) )
		{
			return false;
		}

		$defaultrule = array( 'length' => array('min'=>1, 'max'=>3) );
		if ( !isset($rule['range']) )
		{
			$rule = array_merge($defaultrule, $rule);
		}

		if (isset($rule['range']))
		{
			return $this->checkRange($value, $rule['range']);
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_Integer()


	private function check_Alphabet($value, $rule)
	{
		if ( ! preg_match('/\A[a-zA-Z]*\Z/', $value) )
		{
			return false;
		}

		$defaultrule = array( 'length' => array('min'=>1, 'max'=>16) );
		$rule = array_merge($defaultrule, $rule);

		return $this->checkLength($value, $rule['length']);

	} // end of function check_Alphabet()


	private function check_Number($value, $rule)
	{

		if ( ! preg_match('/\A[+-]{0,1}\d*\.?\d*\Z/', $value) )
		{
			return false;
		}

		$defaultrule = array( 'length' => array('min'=>1, 'max'=>3) );
		$rule = array_merge($defaultrule, $rule);

		if (isset($rule['range']))
		{
			return $this->checkRange($value, $rule['range']);
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_Number()


	private function check_AlphaNum($value, $rule)
	{
		if ( ! preg_match('/\A[a-zA-Z0-9\.]*\Z/', $value) )
		{
			return false;
		}

		$defaultrule = array( 'length' => array('min'=>1, 'max'=>16) );
		$rule = array_merge($defaultrule, $rule);

		return $this->checkLength($value, $rule['length']);

	} // end of function check_AlphaNum()


	private function check_WhiteList($value, $rule)
	{
		$defaultrule = array( 'list' => array() );
		$rule = array_merge($defaultrule, $rule);

		$list = $rule['list'];

		if ( $value === strval(intval($value))  )
		{
			$value = intval($value);
		}
		else if ( $value === strval(floatval($value)) )
		{
			$value = floatval($value);
		}

		return in_array($value, $rule['list'], true);

	} // end of function check_WhiteList()


	private function check_UTF8($value, $rule)
	{
		$defaultrule = array( 'length' => array('min'=>1, 'max'=>3) );
		$rule = array_merge($defaultrule, $rule);

		// L = Alphabet
		// N = Number
		// Z = Separator
		// P = Punctuation(句読記号)
		// S = Symbol(記号)
		// \r, \n
		if ( ! preg_match('/\A[\pL|\pN|\pZ|\pP|\pS]+\Z/u', $value) )
		{
			return false;
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_UTF8()


	private function check_UTF8TEXT($value, $rule)
	{
		$defaultrule = array( 'length' => array('min'=>1, 'max'=>3) );
		$rule = array_merge($defaultrule, $rule);

		// L = Alphabet
		// N = Number
		// Z = Separator
		// P = Punctuation(句読記号)
		// S = Symbol(記号)
		// \r, \n
		if ( ! preg_match('/\A(\pL|\pN|\pZ|\pP|\pS|\r|\n)+\Z/u', $value) )
		{
			return false;
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_UTF8TEXT()


	public function __get($key)
	{
		if ( isset($this->values[$key]) )
		{
			$val = $this->values[$key];
//			unset($this->values[$key]);
			return $val;
		}
		return NULL;
	} // end of function __get()


	public function cookie($key)
	{
		if ( isset($this->cookies[$key]) )
		{
			$val = $this->cookies[$key];
//			unset($this->cookies[$key]);
			return $val;
		}
		return NULL;
	} // end of function cookie()
}

