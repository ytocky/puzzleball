<?php

namespace PuzzleBall\Tests;

class NumberRangeTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_val1' => '12',
			'ok_val2' => '+123',
			'ok_val3' => '-234',
			'ok_val4' => '+3.1415926535',
			'ok_val5' => '-1.41421356',
			'ok_val6' => '-1000',
			'ok_val7' => '+1000',
			'ok_val8' => '1000',
			'ok_val9' => '0',

			'ng_val1' => '1000.1',
			'ng_val2' => 'abc',
			'ng_val3' => '123A',
			'ng_val4' => '-1000.1',
			'ng_val5' => '-1001',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_str = array(
					'type' => \PuzzleBall::NUMBER,
			        'range' => array('min'=>-1000, 'max'=>1000)
		);

		$rules = array(
			'GET' => array(
				'ok_val1'  => $rule_str,
				'ok_val2'  => $rule_str,
				'ok_val3'  => $rule_str,
				'ok_val4'  => $rule_str,
				'ok_val5'  => $rule_str,
				'ok_val6'  => $rule_str,
				'ok_val7'  => $rule_str,
				'ok_val8'  => $rule_str,
				'ok_val9'  => $rule_str,

				'ng_val1'  => $rule_str,
				'ng_val2'  => $rule_str,
				'ng_val3'  => $rule_str,
				'ng_val4'  => $rule_str,
				'ng_val5'  => $rule_str,
			),
		);

		$config['behavior'] = \PuzzleBall::ONGOING;

		$ball = new \PuzzleBall($rules, $config);

		return $ball;
	}

	/**
	 * @depends testNew
	 */
	public function testOKValue($ball) {
		$this->assertSame(12, $ball->ok_val1);
		$this->assertSame(123, $ball->ok_val2);
		$this->assertSame(-234, $ball->ok_val3);
		$this->assertSame(3.1415926535, $ball->ok_val4);
		$this->assertSame(-1.41421356, $ball->ok_val5);
		$this->assertSame(-1000, $ball->ok_val6);
		$this->assertSame(1000, $ball->ok_val7);
		$this->assertSame(1000, $ball->ok_val8);
		$this->assertSame(0, $ball->ok_val9);
	}

	/**
	 *  @depends testNew
	 */
	public function testNGValue($ball)
	{
		$this->assertNull($ball->ng_val1);
		$this->assertNull($ball->ng_val2);
		$this->assertNull($ball->ng_val3);
		$this->assertNull($ball->ng_val4);
		$this->assertNull($ball->ng_val5);
	}
}
