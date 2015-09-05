<?php
// Copyright 2015 ytocky

class Rules
{
	const MAX_NAME = 10;
	const MAX_MESSAGE = 200;

	public static $name = array(
		'type' => PuzzleBall::UTF8,
		'length' => array( 'min' => 1, 'max' => self::MAX_NAME),
	);

	public static $color = array(
		'type' => PuzzleBall::NUMBER,
		'range' => array( 'min' => 0, 'max' => 7),
	);

	public static $message = array(
		'type' => PuzzleBall::UTF8,
		'length' => array( 'min' => 1, 'max' => self::MAX_MESSAGE),
	);
}
