<?php

namespace PuzzleBall\Tests;

class IntegerArray2Test extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$str = 'a[]=1&a[]=200&a[]=3&a[]=4&a[]=5&&b[1]=6&b[10]=7&b[11]=8&b[3]=100&c[hoge]=9&c[key]=10&c[name]=11';
		parse_str($str, $_GET);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_val = array( 'type'=>\PuzzleBall::INTEGER, 'range'=>array('min'=>1, 'max'=>10));
		$rules['GET'] = array(
			'a[]' => array(
				'maxCount' => 3,
				'value' => $rule_val,
			),
			'b[]' => array(
				'index' => array( 'type'=>\PuzzleBall::INTEGER, 'range'=>array('min'=>0, 'max'=>10) ),
				'value' => $rule_val,
			),
			'c[]' => array(
				'index' => array( 'type'=>\PuzzleBall::WHITELIST, 'list'=>array('key','name') ),
				'value' => $rule_val,
			),
		);

		$config['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $config);

		return $ball;
	}

	/**
	 * @depends testNew
	 */
	public function testOKValueA($ball) {
		$this->assertSame(1,$ball->a[0]);
		$this->assertSame(3,$ball->a[1]);
		$this->assertSame(4,$ball->a[2]);
		$this->assertSame( array(0,1,2) , array_keys($ball->a));
	}

	/**
	 * @depends testNew
	 */
	public function testOKValueB($ball) {
		$this->assertSame(6,$ball->b[1]);
		$this->assertSame(7,$ball->b[10]);
		$this->assertNull($ball->b[3]);
		$this->assertSame( array(1,10,3), array_keys($ball->b) );
	}

	/**
	 * @depends testNew
	 */
	public function testOKValueC($ball) {
		$this->assertSame(10,$ball->c['key']);
		$this->assertNull($ball->c['name']);
		$this->assertSame( array('key','name'), array_keys($ball->c) );
	}
}
