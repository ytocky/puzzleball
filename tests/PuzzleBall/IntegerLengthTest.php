<?php

namespace PuzzleBall\Tests;

class IntegerLengthTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_id1' => '1',
			'ok_id2' => '50',
			'ok_id3' => '1000',
			'ok_id4' => '+831',
			'ok_id5' => '-100',

			'ng_id1' => '10000',
			'ng_id2' => '1.23',
			'ng_id3' => '+1000',
			'ng_id4' => '-1000',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_id = array(
					'type' => \PuzzleBall::INTEGER,
			        'length' => array('min'=>1, 'max'=>4)
		);

		$rules = array(
			'GET' => array(
				'ok_id1'  => $rule_id,
				'ok_id2'  => $rule_id,
				'ok_id3'  => $rule_id,
				'ok_id4'  => $rule_id,
				'ok_id5'  => $rule_id,

				'ng_id1'  => $rule_id,
				'ng_id2'  => $rule_id,
				'ng_id3'  => $rule_id,
				'ng_id4'  => $rule_id,
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
		$this->assertSame(1, $ball->ok_id1);
		$this->assertSame(50, $ball->ok_id2);
		$this->assertSame(1000, $ball->ok_id3);
		$this->assertSame(831, $ball->ok_id4);
		$this->assertSame(-100, $ball->ok_id5);
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
