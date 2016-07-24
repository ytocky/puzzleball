<?php

namespace PuzzleBall\Tests;

class SourceGetFromQueryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_ENV['QUERY_STRING'] = 'ok_id1=1&ok_id2=50&ok_id3=100&ok_id4=%2B100';

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_id = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>100)
		);

		$rules = array(
			'GET' => array(
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
		$this->assertSame(1, $ball->ok_id1);
		$this->assertSame(50, $ball->ok_id2);
		$this->assertSame(100, $ball->ok_id3);
		$this->assertSame(100, $ball->ok_id4);
	}

}
