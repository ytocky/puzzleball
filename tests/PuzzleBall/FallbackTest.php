<?php

namespace PuzzleBall\Tests;

class FallbackTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ng_get' => '999',
		);
		$_POST = array(
			'ng_post' => '999',
		);
		$_COOKIE = array(
			'ng' => '999',
		);
		$_ENV = array(
			'ng' => '999',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_str = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>10)
		);

		$rules = array(
			'GET' => array( 'ng_get' => $rule_str, ),
			'POST' => array( 'ng_post' => $rule_str, ),
			'COOKIE' => array( 'ng' => $rule_str, ),
			'ENV' => array( 'ng' => $rule_str, ),
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
}
