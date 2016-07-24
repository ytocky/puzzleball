<?php

namespace PuzzleBall\Tests;

class PatternTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'p1_ok_val1' => 'e',

			'p1_ng_val1' => 'g',
			'p1_ng_val2' => '5',
			'p1_ng_val3' => 'aa',

			'p2_ok_val1' => 'A',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_p1 = array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/\A[a-f]\Z/',
		);
		$rule_p2 = array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/\A[a-f]\Z/i',
		);

		$rules = array(
			'GET' => array(
				'p1_ok_val1'  => $rule_p1,
				'p1_ng_val1'  => $rule_p1,
				'p1_ng_val2'  => $rule_p1,
				'p1_ng_val3'  => $rule_p1,

				'p2_ok_val1'  => $rule_p2,
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
		$this->assertEquals('e', $ball->p1_ok_val1);
		$this->assertEquals('A', $ball->p2_ok_val1);
	}

	/**
	 *  @depends testNew
	 */
	public function testNGValue($ball)
	{
		$this->assertNull($ball->p1_ng_val1);
		$this->assertNull($ball->p1_ng_val2);
		$this->assertNull($ball->p1_ng_val3);
	}
}
