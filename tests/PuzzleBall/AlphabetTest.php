<?php

namespace PuzzleBall\Tests;

class AlphabetTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_str1' => 'a',
			'ok_str2' => 'PuzzleBall',
			'ok_str3' => 'bcdefghxyz',

			'ng_str1' => '',
			'ng_str2' => 'aaaaaaaaaaa',
			'ng_str3' => 'ABC%dDEFGH',
			'ng_str4' => 'ABC123DEF',
			'ng_str5' => '123',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_str = array(
					'type' => \PuzzleBall::ALPHABET,
			        'length' => array('min'=>1, 'max'=>10)
		);

		$rules = array(
			'GET' => array(
				'ok_str1'  => $rule_str,
				'ok_str2'  => $rule_str,
				'ok_str3'  => $rule_str,

				'ng_str1'  => $rule_str,
				'ng_str2'  => $rule_str,
				'ng_str3'  => $rule_str,
				'ng_str4'  => $rule_str,
				'ng_str5'  => $rule_str,

				'no_str1'  => $rule_str,
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
		$this->assertEquals('a', $ball->ok_str1);
		$this->assertEquals('PuzzleBall', $ball->ok_str2);
		$this->assertEquals('bcdefghxyz', $ball->ok_str3);
	}

	/**
	 *  @depends testNew
	 */
	public function testNGValue($ball)
	{
		$this->assertNull($ball->ng_str1);
		$this->assertNull($ball->ng_str2);
		$this->assertNull($ball->ng_str3);
		$this->assertNull($ball->ng_str4);
		$this->assertNull($ball->ng_str5);

		$this->assertNull($ball->no_str1);
	}
}
