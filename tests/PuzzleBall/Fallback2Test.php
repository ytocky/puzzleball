<?php

namespace PuzzleBall\Tests;

class Fallback2Test extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ng_get' => '999',
			'ng_get2' => '999',
		);
		$_POST = array(
			'ng_post' => '999',
			'ng_post2' => '999',
		);
		$_COOKIE = array(
			'ng' => '999',
			'ng2' => '999',
		);
		$_ENV = array(
			'ng' => '999',
			'ng2' => '999',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_str = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>10)
		);
		$rule_str2 = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>10),
					'fallback' => 'NG2',
		);

		$rules = array(
			'GET' => array( 'ng_get' => $rule_str, 'ng_get2' => $rule_str2, ),
			'POST' => array( 'ng_post' => $rule_str, 'ng_post2' => $rule_str2, ),
			'COOKIE' => array( 'ng' => $rule_str, 'ng2' => $rule_str2, ),
			'ENV' => array( 'ng' => $rule_str, 'ng2' => $rule_str2, ),
		);

		$config['behavior'] = \PuzzleBall::ONGOING;
		$config['fallback'] = 'NG';

		$ball = new \PuzzleBall($rules, $config);

		return $ball;
	}

	/**
	 * @depends testNew
	 */
	public function testFallbackValue($ball) {
		$this->assertSame('NG', $ball->ng_get);
		$this->assertSame('NG', $ball->ng_post);
		$this->assertSame('NG', $ball->cookie('ng'));
		$this->assertSame('NG', $ball->env('ng'));
	}

	/**
	 * @depends testNew
	 */
	public function testFallback2Value($ball) {
		$this->assertSame('NG2', $ball->ng_get2);
		$this->assertSame('NG2', $ball->ng_post2);
		$this->assertSame('NG2', $ball->cookie('ng2'));
		$this->assertSame('NG2', $ball->env('ng2'));
	}
}
