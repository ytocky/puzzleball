<?php
// Copyright 2015 ytocky

class Message
{
	const FILENAME = './data.txt';
	const LINES = 100;

	public function add($color, $name, $message)
	{
		$fp = fopen(self::FILENAME, 'r+');
		if ($fp === FALSE) {
			throw Exception();
		}
		if (flock($fp, LOCK_EX) === FALSE) {
			throw Exception();
		}
		$oldlines = $this->read($fp);
		$newline = time() . ",$color,$name,$message";
		$lines = array_merge(array($newline), $oldlines);
		ftruncate($fp, 0);
		fseek($fp,0);
		$this->write($fp, $lines);
		fclose($fp);
	}

	public function get()
	{
		$fp = fopen(self::FILENAME, 'r');
		if ($fp === FALSE) {
			return array();
		}
		if (flock($fp, LOCK_SH) === FALSE) {
			fclose($fp);
			return array();
		}
		$lines = $this->read($fp);
		fclose($fp);

		foreach ($lines as $line) {
			$ary = split(',', $line, 4);
			$msgs[] = array('time'=>$ary[0], 'color'=>$ary[1], 'name'=>$ary[2], 'message'=>$ary[3]);
		}
		return $msgs;
	}

	private function read($fp)
	{
		$lines = array();
		$count = 0;
		while($count < self::LINES) {
			$line = fgets($fp);
			if ($line === FALSE) {
				break;
			}
			$lines[] = chop($line);
			$count++;
		}
		return $lines;
	}

	private function write($fp, $lines)
	{
		$count = 0;
		while( ($count < self::LINES) && ($count < count($lines)) ) {
			fputs($fp, $lines[$count]."\n");
			$count++;
		}
		return $lines;
	}

	public function clear()
	{
		$fp = fopen(self::FILENAME, 'r+');
		if ($fp === FALSE) {
			throw Exception();
		}
		if (flock($fp, LOCK_EX) === FALSE) {
			throw Exception();
		}
		ftruncate($fp, 0);
		fseek($fp,0);
		fclose($fp);
	}
}
