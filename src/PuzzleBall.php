<?php
// パラメータ受け入れクラス PuzzleBall
// Copyright 2016 ytocky

require_once "BallException.php";

class PuzzleBall
{
	/*  ルールは、以下の種類を提供。
		PATTERN		// パターン
		INTEGER		// 整数のみ        (数値型への変換あり)
		ALPHABET	// a-z, A-Z
		NUMBER		// 0-9, +, -, .    (数値型への変換あり)
		DIGIT		// 0-9, +, -, .
		ALPHADIGIT	// 0-9, a-z, A-Z
		WHITELIST	// 期待値を列挙した、ホワイトリスト形式
		UTF8		// UTF8(改行なし)
		UTF8TEXT	// UTF8(改行を許容)
	*/
	const PATTERN	= 'Pattern';
	const INTEGER	= 'Integer';
	const ALPHABET	= 'Alphabet';
	const NUMBER	= 'Number';
	const DIGIT 	= 'Digit';
	const ALPHADIGIT= 'AlphaDigit';
	const WHITELIST	= 'WhiteList';
	const UTF8		= 'UTF8';
	const UTF8TEXT	= 'UTF8Text';

	// for conf-behavior
	const EXCEPTION = 100;
	const ONGOING = 101;

	private static $pathPost = 'php://stdin';
	private static $cliPostMax = 10240;

	private $values = array();
	private $cookies = array();
	private $envs = array();

	private static $BAK_GET = array();
	private static $BAK_POST = array();
	private static $BAK_ENV = array();
	private static $BAK_COOKIE = array();

	private $conf;
	private $defaultConf = array(
		'behavior' => '',
		'fallback' => NULL,
	);

	public static function make()
	{
		// for CLI
		if (count($_GET) == 0)
		{
			self::getGET();
		}
		if (count($_POST) == 0)
		{
			self::getPOST();
		}
		if (count($_COOKIE) == 0)
		{
			self::getCOOKIE();
		}

		if ( count($_GET)>0 && count(self::$BAK_GET)==0 )
		{
			self::$BAK_GET = $_GET;
		}
		if ( count($_POST)>0 && count(self::$BAK_POST)==0 )
		{
			self::$BAK_POST = $_POST;
		}
		if ( count($_ENV)>0 && count(self::$BAK_ENV)==0 )
		{
			self::$BAK_ENV = $_ENV;
		}
		if ( count($_COOKIE)>0 && count(self::$BAK_COOKIE)==0 )
		{
			self::$BAK_COOKIE = $_COOKIE;
		}

		/* all inputs are clear */
		unset($_GET);
		unset($_POST);
		unset($_ENV);
		unset($_COOKIE);
	}


	public function __construct($rules, $conf = NULL)
	{
		$this->conf = is_null($conf) ? $this->defaultConf : array_merge($this->defaultConf, $conf);

		$this->add($rules);

	} // end of function __construct()


	private static function getGET()
	{
		if (isset($_ENV['QUERY_STRING']))
		{
			parse_str($_ENV['QUERY_STRING'], $_GET);
		}
	}


	private static function getPOST()
	{
		if (isset($_ENV['CONTENT_LENGTH']))
		{
			if (defined('PUZZLEBALL_CLI_POST_MAX'))
			{
				$cliPostMax = PUZZLEBALL_CLI_POST_MAX;
			}
			else
			{
				$cliPostMax = self::$cliPostMax;
			}

			if (defined('PUZZLEBALL_POST_FILE_PATH'))
			{
				$path = PUZZLEBALL_POST_FILE_PATH;
			}
			else
			{
				// @codeCoverageIgnoreStart
				$path = self::$pathPost;
				// @codeCoverageIgnoreEnd
			}
			$fp = fopen($path,'r');
			$maxlength = min($cliPostMax, $_ENV['CONTENT_LENGTH']);
			$data = fread($fp, $maxlength);
			fclose($fp);
			parse_str($data, $_POST);
		}
	}

	private static function getCOOKIE()
	{
		if (isset($_ENV['HTTP_COOKIE']))
		{
			$params = explode(';', $_ENV['HTTP_COOKIE']);
			foreach ($params as $param) {
				list($key, $val) = explode('=', $param, 2);
				$key = trim($key);
				$val = trim($val);
				$_COOKIE[$key] = urldecode($val);
			}
		}
	}

	public function add($rules)
	{
		if (isset($rules['GET'])) {
			$this->check(self::$BAK_GET, $rules['GET'], $this->values);
		} // end of if (isset($rules['GET']))

		if (isset($rules['POST'])) {
			$this->check(self::$BAK_POST, $rules['POST'], $this->values);
		} // end of if (isset($rules['POST']))

		if (isset($rules['ENV'])) {
			$this->check(self::$BAK_ENV, $rules['ENV'], $this->envs);
		} // end of if (isset($rules['ENV']))

		if (isset($rules['COOKIE'])) {
			$this->check(self::$BAK_COOKIE, $rules['COOKIE'], $this->cookies);
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
	private function checkArrayIndexAndValue($key, $index, $value, $rules, &$storage)
	{
		// index check
		$result_index = $this->checkone($index, $rules['index']);

		if ($result_index['result'])
		{
			$result_val = $this->checkone($value, $rules['value']);
			if ($result_val['result'])
			{
				$storage[$key][$index] = $result_val['value'];
			}
			else
			{
				$this->assignFalseValue( $storage[$key][$index], $rules['value']);
			}
		}
		else
		{
			$this->actionFail();
		}
	}

	// 'maxCount' and 'value'
	private function checkArrayValue($key, $value, $rules, &$storage)
	{
		// count check
		$count = (isset($storage[$key])) ? count($storage[$key]) : 0;
		$result = ($count < $rules['maxCount']);

		if ($result)
		{
			$result = $this->checkone($value, $rules['value']);
			if ($result['result'])
			{
				$storage[$key][] = $result['value'];
			}
			else
			{
				$this->actionFail();
			}
		}
		else
		{
			$this->actionFail();
		}
	}


	private function checkArray($key, $params, $rule, &$storage)
	{

		if ( isset($rule['value']) )
		{

			if ( isset($rule['index']) )
			{
				foreach ($params as $index => $v)
				{
					$this->checkArrayIndexAndValue($key, $index, $v, $rule, $storage);
				}
			} // end of if ( isset($rule['index']) )
			else if ( isset($rule['maxCount']) )
			{
				foreach ($params as $v)
				{
					$this->checkArrayValue($key, $v, $rule, $storage);
				}
			} // end of else if ( isset($rule['maxCount']) )

		} // end of if ( isset($rule['value']) )

	}  // end of checkArray()


	private function checkValue($key, $value, $rule, &$storage)
	{
		$result = $this->checkone($value, $rule);

		if ($result['result'])
		{
			$storage[$key] = $result['value'];
		}
		else
		{
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
					if ( isset($rule['fallback']) )
					{
						$target = $rule['fallback'];
					}
					else if ( isset($this->conf['fallback']) )
					{
						$target = $this->conf['fallback'];
					}
					else
					{
						$target = NULL;
					}
					return;
			}
		}

		throw new PuzzleBall\BallException();

	}

	private function actionFail()
	{
		if (isset($this->conf['behavior']) && $this->conf['behavior'] == self::ONGOING)
		{
			return;
		}

		throw new PuzzleBall\BallException();
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
			return array('result'=>true, 'value'=>$value );
		}
		return array('result'=>false, 'value'=>NULL );
	}  // end of function checkRange()


	protected function checkLength($value, $length)
	{
		$min = $length['min'];
		$max = $length['max'];
		$length = mb_strlen($value, 'UTF-8');
		if ($min <= $length && $length <= $max)
		{
			return array('result'=>true, 'value'=>$value);
		}
		return array('result'=>false, 'value'=>NULL );
	}  // end of function checkLength()


	private function check_Pattern($value, $rule)
	{
		return array('result'=>preg_match($rule['pattern'], $value), 'value'=>$value);
	} // end of function check_Pattern()


	private function check_Integer($value, $rule)
	{

		if ( $value === "" || !preg_match('/\A[+-]?[0-9]+\z/',$value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		if (isset($rule['range']))
		{
			$result = $this->checkRange($value, $rule['range']);
			$result['value'] = ($result['result']) ? (int)$value : NULL;
			return $result;
		}

		$result = $this->checkLength($value, $rule['length']);
		$result['value'] = ($result['result']) ? (int)$value : NULL;
		return $result;

	} // end of function check_Integer()


	private function check_Alphabet($value, $rule)
	{
		if ( ! preg_match('/\A[a-zA-Z]*\z/', $value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_Alphabet()


	private function check_Number($value, $rule)
	{

		if ( ! preg_match('/\A[+-]{0,1}\d*\.?\d*\z/', $value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		if (isset($rule['range']))
		{
			$result = $this->checkRange($value, $rule['range']);
		}
		else
		{
			$result = $this->checkLength($value, $rule['length']);
		}

		if ( preg_match('/\./', $value) )
		{
			$value = (float)$value;
		}
		else
		{
			$value = (int)$value;
		}

		$result['value'] = $value;
		return $result;

	} // end of function check_Number()


	private function check_Digit($value, $rule)
	{

		if ( ! preg_match('/\A[+-]{0,1}\d*\.?\d*\z/', $value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_Digit()


	private function check_AlphaDigit($value, $rule)
	{
		if ( ! preg_match('/\A[a-zA-Z0-9\.]*\z/', $value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_AlphaDigit()


	private function check_WhiteList($value, $rule)
	{
		$list = $rule['list'];

		if ( $value === (string)((int)$value)  )
		{
			$value = (int)$value;
		}
		else if ( $value === (string)((float)$value) )
		{
			$value = (float)$value;
		}

		return array('result'=>in_array($value, $rule['list'], true), 'value'=>$value);

	} // end of function check_WhiteList()


	private function check_UTF8($value, $rule)
	{
		// L = Alphabet
		// N = Number
		// Z = Separator
		// P = Punctuation(句読記号)
		// S = Symbol(記号)
		if ( ! preg_match('/\A[\pL|\pN|\pZ|\pP|\pS]+\z/u', $value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_UTF8()


	private function check_UTF8TEXT($value, $rule)
	{
		// L = Alphabet
		// N = Number
		// Z = Separator
		// P = Punctuation(句読記号)
		// S = Symbol(記号)
		// \r, \n, \t
		if ( ! preg_match('/\A(\pL|\pN|\pZ|\pP|\pS|\r|\n|\t)+\z/u', $value) )
		{
			return array('result'=>false, 'value'=>NULL );
		}

		return $this->checkLength($value, $rule['length']);

	} // end of function check_UTF8TEXT()


	public function __get($key)
	{
		if ( isset($this->values[$key]) )
		{
			$val = $this->values[$key];
			return $val;
		}
		return NULL;
	} // end of function __get()


	public function cookie($key)
	{
		if ( isset($this->cookies[$key]) )
		{
			$val = $this->cookies[$key];
			return $val;
		}
		return NULL;
	} // end of function cookie()


	public function env($key)
	{
		if ( isset($this->envs[$key]) )
		{
			$val = $this->envs[$key];
			return $val;
		}
		return NULL;
	} // end of function env()

}

PuzzleBall::make();

