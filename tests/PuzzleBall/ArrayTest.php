<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class ArrayTest extends \PHPUnit_Framework_TestCase
{
	public function testSeq()
	{
		$rules = array(
			'GET' => array(
				'id[]' => array(
					'key' => array(
						'type'=>\PuzzleBall::NUMBER,
						'length'=>array('min'=>1, 'max'=>1),
					),
					'value' => array(
						'type'=>\PuzzleBall::NUMBER,
						'range'=>array('min'=>1, 'max'=>99),
					),
				),
			),
		);

		$str = 'id[]=1&id[]=5&id[]=10&id[]=100&id[]=50';
		parse_str($str, $_GET);

		$this->setExpectedException('PuzzleBall\BallException');

		$ball = new \PuzzleBall($rules);
	}

	public function testSeqBehavior()
	{
		$rules = array(
			'GET' => array(
				'id[]' => array(
					'key' => array(
						'type'=>\PuzzleBall::NUMBER,
						'length'=>array('min'=>1, 'max'=>1),
					),
					'value' => array(
						'type'=>\PuzzleBall::NUMBER,
						'range'=>array('min'=>1, 'max'=>99),
					),
				),
			),
		);

		$str = 'id[]=1&id[]=5&id[]=10&id[]=100&id[]=50';
		parse_str($str, $_GET);

		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);
		$ids = $ball->id;

		$this->assertEquals('1', $ids[0]);
		$this->assertEquals('5', $ids[1]);
		$this->assertEquals('10', $ids[2]);
		$this->assertNull($ids[3]);
		$this->assertEquals('50', $ids[4]);
	}

	public function testSeqFallback()
	{
		$rules = array(
			'GET' => array(
				'id[]' => array(
					'key' => array(
						'type'=>\PuzzleBall::NUMBER,
						'length'=>array('min'=>1, 'max'=>1),
					),
					'value' => array(
						'type'=>\PuzzleBall::NUMBER,
						'range'=>array('min'=>1, 'max'=>99),
						'fallback' => -1,
					),
				),
			),
		);

		$str = 'id[]=1&id[]=5&id[]=10&id[]=100&id[]=50';
		parse_str($str, $_GET);

		$ball = new \PuzzleBall($rules);
		$ids = $ball->id;

		$this->assertEquals('1', $ids[0]);
		$this->assertEquals('5', $ids[1]);
		$this->assertEquals('10', $ids[2]);
		$this->assertEquals(-1, $ids[3]);
		$this->assertEquals('50', $ids[4]);
	}

	public function testIndex()
	{
		$rules = array(
			'POST' => array(
				'cmd' => array(
					'type' => \PuzzleBall::WHITELIST,
					'list' => array( 'new', 'update', 'delete'),
				),
				'user[]' => array(
					'key' => array(
						'type'=>\PuzzleBall::ALPHABET_NUMBER,
						'length'=>array('min'=>1, 'max'=>10)
					),
					'value' => array(
						'type'=>\PuzzleBall::UTF8,
						'length'=>array('min'=>1, 'max'=>99)
					),
				),
				'tel[]' => array(
					'key' => array(
						'type' => \PuzzleBall::NUMBER,
						'length' => array( 'min'=>1, 'max'=>1 ),
					),
					'value' => array(
						'type' => \PuzzleBall::NUMBER,
						'length' => array( 'min'=>1, 'max'=>5 ),
					),
				),
			),
		);

		$str = 'cmd=new&user[name]=PHP%20User&user[message]=%e3%81%93%e3%82%93%e3%81%ab%e3%81%a1%e3%81%af&user[age]=10&tel[a]=012&tel[1]=345&tel[2]=6789';
		parse_str($str, $_POST);

		$ball = new \PuzzleBall($rules);

		$this->assertEquals('new', $ball->cmd);

		$user = $ball->user;
		$this->assertEquals('PHP User', $user['name']);
		$this->assertEquals('こんにちは', $user['message']);
		$this->assertEquals('10', $user['age']);

		$tel = $ball->tel;
		$this->assertArrayNotHasKey(0,$tel);
		$this->assertEquals(345, $tel[1]);
		$this->assertEquals(6789, $tel[2]);
		$this->assertFalse(isset($tel['a']));
	}

	public function testSubArray()
	{
		$rules = array(
			'POST' => array(
				'user[]' => array(
					'keyvalue' => array(
						'name' => array(
							'type' => \PuzzleBall::UTF8,
							'length' => array( 'min' => 1, 'max' => 20),
						),
						'age' => array(
							'type' => \PuzzleBall::INTEGER,
							'range' => array( 'min' => 0, 'max' => 150),
						),
						'addr' => array(
							'type' => \PuzzleBall::UTF8,
							'length' => array( 'min' => 1, 'max' => 100),
						),
					),
				),
			),
		);

		$str  = 'user[name]=alice&user[age]=20&user[ill]=foobar&user[addr]=Chiyoda%20Ku%2c%20Tokyo';
		parse_str($str, $_POST);

		$ball = new \PuzzleBall($rules);

		$user = $ball->user;
		$this->assertCount(3, $user);
		$this->assertEquals('alice', $user['name']);
		$this->assertEquals(20, $user['age']);
		$this->assertEquals('Chiyoda Ku, Tokyo', $user['addr']);
		
	}
/*
	public function testSubArray2()
	{
		$rules = array(
			'POST' => array(
				'user[][]' => array(
					'keyvalue' => array(
						'name' => array(
							'type' => \PuzzleBall::UTF8,
							'length' => array( 'min' => 1, 'max' => 20),
						),
						'age' => array(
							'type' => \PuzzleBall::INTEGER,
							'range' => array( 'min' => 0, 'max' => 150),
						),
						'addr' => array(
							'type' => \PuzzleBall::UTF8,
							'length' => array( 'min' => 1, 'max' => 100),
						),
					),
				),
			),
		);

		$str  = 'user[0][name]=alice&user[0][age]=20&user[0][addr]=Chiyoda%20Ku%2c%20Tokyo';
		$str .= 'user[1][name]=Bob&user[1][age]=50&user[1][addr]=Address%20Address&user[1][ill]=foobar';
		parse_str($str, $_POST);

		$ball = new \PuzzleBall($rules);

		$this->assertCount(2, $ball->user);
		$this->assertCount(3, $ball->user[0]);
		$this->assertCount(3, $ball->user[1]);
		
	}
*/
}
