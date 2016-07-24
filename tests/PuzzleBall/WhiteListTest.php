<?php

namespace PuzzleBall\Tests;

class WhiteListTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_val1' => '1',
			'ok_val2' => '2',
			'ok_val3' => '3',
			'ok_val4' => '5',
			'ok_val5' => '3.14',
			'ok_val6' => 'ABC',

			'ng_val1' => '4',
			'ng_val2' => '1ABC',

			'ok_pc1' => 'MSX2+',
			'ok_pc2' => 'Amiga',

			'ng_pc1' => 'Famicom',

		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_val = array(
					'type' => \PuzzleBall::WHITELIST,
					'list' => array( 1, 2, 3, 5, 3.14, 'ABC', ),
		);
		$rule_pc = array(
					'type' => \PuzzleBall::WHITELIST,
					'list' => array( 'IBM-PC', 'Macintosh', 'Amiga', 'MSX2+', 'PC-9801', ),
		);

		$rules = array(
			'GET' => array(
				'ok_val1'  => $rule_val,
				'ok_val2'  => $rule_val,
				'ok_val3'  => $rule_val,
				'ok_val4'  => $rule_val,
				'ok_val5'  => $rule_val,
				'ok_val6'  => $rule_val,

				'ng_val1'  => $rule_val,
				'ng_val2'  => $rule_val,

				'ok_pc1'  => $rule_pc,
				'ok_pc2'  => $rule_pc,

				'ng_pc1'  => $rule_pc,
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
		$this->assertEquals(1, $ball->ok_val1);
		$this->assertEquals(2, $ball->ok_val2);
		$this->assertEquals(3, $ball->ok_val3);
		$this->assertEquals(5, $ball->ok_val4);
		$this->assertEquals(3.14, $ball->ok_val5);
		$this->assertEquals('ABC', $ball->ok_val6);

		$this->assertEquals('MSX2+', $ball->ok_pc1);
		$this->assertEquals('Amiga', $ball->ok_pc2);
	}

	/**
	 *  @depends testNew
	 */
	public function testNGValue($ball)
	{
		$this->assertNull($ball->ng_val1);
		$this->assertNull($ball->ng_val2);

		$this->assertNull($ball->ng_pc1);
	}
}
