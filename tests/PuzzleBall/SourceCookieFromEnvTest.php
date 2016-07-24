<?php

namespace PuzzleBall\Tests;

class SourceCookieFromEnvTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_ENV['HTTP_COOKIE'] = 'ok_id1=1; ok_id2=50; ok_id3=100; ok_id4=%2B100';

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_id = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>100)
		);

		$rules = array(
			'COOKIE' => array(
				'ok_id1'  => $rule_id,
				'ok_id2'  => $rule_id,
				'ok_id3'  => $rule_id,
				'ok_id4'  => $rule_id,
			),
		);

		$ball = new \PuzzleBall($rules);

		return $ball;
	}

	/**
	 * @depends testNew
	 */
	public function testOKValue($ball) {
		$this->assertSame(1, $ball->cookie('ok_id1'));
		$this->assertSame(50, $ball->cookie('ok_id2'));
		$this->assertSame(100, $ball->cookie('ok_id3'));
		$this->assertSame(100, $ball->cookie('ok_id4'));
	}

}
