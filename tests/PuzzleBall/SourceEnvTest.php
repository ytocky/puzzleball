<?php

namespace PuzzleBall\Tests;

class SourceEnvTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_ENV = array(
			'HTTP_USER_AGENT' => 'foobar(compatible / 1.0)',
			'HTTP_METHOD' => 'GET',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_agent = array(
					'type' => \PuzzleBall::UTF8,
			        'length' => array('min'=>1, 'max'=>100)
		);

		$rules = array(
			'ENV' => array(
				'HTTP_USER_AGENT'  => $rule_agent,
				'undef_id'  => $rule_agent,
			),
		);

		$ball = new \PuzzleBall($rules);

		return $ball;
	}

	/**
	 * @depends testNew
	 */
	public function testOKValue($ball) {
		$this->assertSame('foobar(compatible / 1.0)', $ball->env('HTTP_USER_AGENT'));
	}

	/**
	 * @depends testNew
	 */
	public function testUndefValue($ball) {
		$this->assertNull($ball->env('undef_id'));
	}

	/**
	 * @depends testNew
	 */
	public function testUnRuledParam($ball) {
		$this->assertNull($ball->env('HTTP_METHOD'));
	}

}
