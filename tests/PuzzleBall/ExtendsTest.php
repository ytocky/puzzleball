<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class ExtBall extends \PuzzleBall
{
	const PASSWORD = 'Password';

	protected function check_Password($value, $dummyrule)
	{
		$rule = array(
			'type' => ExtBall::UTF8,
			'length' => array( 'min'=>8, 'max'=>20 ),
		);

		$result = $this->checkone($value, $rule);
		if ( !$result)
		{
			return false;
		}

		if (preg_match_all('/[a-z]/', $value) == 0)
		{
			return false;
		}

		if (preg_match_all('/[A-Z]/', $value) == 0)
		{
			return false;
		}

		if (preg_match_all('/[0-9]/', $value) == 0)
		{
			return false;
		}

		if (preg_match_all('/[\x21-\x2F\x3A-\x40\x5B-\x60\x7B-\x7E]/', $value) == 0)
		{
			return false;
		}

		return true;
	}
}


class ExtendsTest extends \PHPUnit_Framework_TestCase
{
	public function testAddType()
	{
		$rulepass = array( 'type' => ExtBall::PASSWORD );
		$rule['GET'] = array(
			'pass1' => $rulepass,
			'pass2' => $rulepass,
			'pass3' => $rulepass,
			'pass4' => $rulepass,
		);

		$_GET = array(
			'pass1' => 'ABCdef123!"#',
			'pass2' => 'Ad1@',
			'pass3' => '1234567890',
			'pass4' => 'passwordiloveyou',
		);


		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new ExtBall($rule, $conf);

		$this->assertEquals('ABCdef123!"#', $ball->pass1);
		$this->assertNull($ball->pass2);
		$this->assertNull($ball->pass3);
		$this->assertNull($ball->pass4);
	}
}
