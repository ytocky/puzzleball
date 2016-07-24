<?php

namespace PuzzleBall\Tests;

class AlphaDigitTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_val1' => 'a',
			'ok_val2' => 'PuzzleBall',
			'ok_val3' => 'bcdefghxyz',
			'ok_val4' => 'ABC123DEF',
			'ok_val5' => 'AB1.23',
			'ok_val6' => '1',
			'ok_val7' => '9876543210',
			'ok_val8' => '123A',
			'ok_val9' => 'H2A',
			'ok_val10' => '123.456789',

			'ng_val1' => '',
			'ng_val2' => 'aaaaaaaaaaa',
			'ng_val3' => 'ABC%30DEFG',
			'ng_val4' => '12345678901',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_val = array(
					'type' => \PuzzleBall::ALPHADIGIT,
			        'length' => array('min'=>1, 'max'=>10)
		);

		$rules = array(
			'GET' => array(
				'ok_val1'  => $rule_val,
				'ok_val2'  => $rule_val,
				'ok_val3'  => $rule_val,
				'ok_val4'  => $rule_val,
				'ok_val5'  => $rule_val,
				'ok_val6'  => $rule_val,
				'ok_val7'  => $rule_val,
				'ok_val8'  => $rule_val,
				'ok_val9'  => $rule_val,
				'ok_val10'  => $rule_val,

				'ng_val1'  => $rule_val,
				'ng_val2'  => $rule_val,
				'ng_val3'  => $rule_val,
				'ng_val4'  => $rule_val,

				'no_val1'  => $rule_val,
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
		$this->assertEquals('a', $ball->ok_val1);
		$this->assertEquals('PuzzleBall', $ball->ok_val2);
		$this->assertEquals('bcdefghxyz', $ball->ok_val3);
		$this->assertEquals('ABC123DEF', $ball->ok_val4);
		$this->assertEquals('AB1.23', $ball->ok_val5);
		$this->assertEquals('1', $ball->ok_val6);
		$this->assertEquals('9876543210', $ball->ok_val7);
		$this->assertEquals('123A', $ball->ok_val8);
		$this->assertEquals('H2A', $ball->ok_val9);
		$this->assertEquals('123.456789', $ball->ok_val10);
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

		$this->assertNull($ball->no_val1);
	}
}
