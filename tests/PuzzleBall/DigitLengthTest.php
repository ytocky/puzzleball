<?php

namespace PuzzleBall\Tests;

class DigitLengthTest extends \PHPUnit_Framework_TestCase
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
			'ok_val4' => '+3.14',
			'ok_val5' => '-1.4142135',
			'ok_val6' => '2147483647',

			'ng_val1' => '12345678901',
			'ng_val2' => 'abc',
			'ng_val3' => '123A',
			'ng_val4' => '123.4567890',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_str = array(
					'type' => \PuzzleBall::DIGIT,
			        'length' => array('min'=>1, 'max'=>10)
		);

		$rules = array(
			'GET' => array(
				'ok_val1'  => $rule_str,
				'ok_val2'  => $rule_str,
				'ok_val3'  => $rule_str,
				'ok_val4'  => $rule_str,
				'ok_val5'  => $rule_str,
				'ok_val6'  => $rule_str,

				'ng_val1'  => $rule_str,
				'ng_val2'  => $rule_str,
				'ng_val3'  => $rule_str,
				'ng_val4'  => $rule_str,
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
		$this->assertSame('12', $ball->ok_val1);
		$this->assertSame('+123', $ball->ok_val2);
		$this->assertSame('-234', $ball->ok_val3);
		$this->assertSame('+3.14', $ball->ok_val4);
		$this->assertSame('-1.4142135', $ball->ok_val5);
		$this->assertSame('2147483647', $ball->ok_val6);
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
	}
}
