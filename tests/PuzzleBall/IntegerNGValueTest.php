<?php

namespace PuzzleBall\Tests;

class IntegerNGValueTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_id1' => '1',
			'ok_id2' => '40',
			'ok_id3' => '100',

			'ng_id1' => 'str',
			'ng_id2' => '12a',
			'ng_id3' => '',
			'ng_id4' => '0',
			'ng_id5' => '101',
			'ng_id6' => '3.1',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_id = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>100)
		);

		$config['behavior'] = \PuzzleBall::ONGOING;

		$rules = array(
			'GET' => array(
				'ok_id1'  => $rule_id,
				'ok_id2'  => $rule_id,
				'ok_id3'  => $rule_id,

				'ng_id1'  => $rule_id,
				'ng_id2'  => $rule_id,
				'ng_id3'  => $rule_id,
				'ng_id4'  => $rule_id,
				'ng_id5'  => $rule_id,
				'ng_id6'  => $rule_id,
			),
		);

		$ball = new \PuzzleBall($rules, $config);

		return $ball;
	}

	/**
	 *  @depends testNew
	 */
	public function testOKValue($ball)
	{
		$this->assertSame(1, $ball->ok_id1);
		$this->assertSame(40, $ball->ok_id2);
		$this->assertSame(100, $ball->ok_id3);
	}

	/**
	 *  @depends testNew
	 */
	public function testNGValue($ball)
	{
		$this->assertNull($ball->ng_id1);
		$this->assertNull($ball->ng_id2);
		$this->assertNull($ball->ng_id3);
		$this->assertNull($ball->ng_id4);
		$this->assertNull($ball->ng_id5);
		$this->assertNull($ball->ng_id6);
	}

}
