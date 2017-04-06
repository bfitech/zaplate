<?php


use PHPUnit\Framework\TestCase;
use BFITech\ZapTemplate\Template;


class Filter {

	public static function filter_string($val) {
		if (!is_string($val))
			return $val;
		return 'xx' . $val;
	}

	public static function filter_array($val) {
		if (!is_array($val))
			return $val;
		return array_map(function($ival){
			if (is_numeric($ival))
				return intval($ival) + 1;
			return 'yy' . $ival;
		}, $val);
	}

}

class TemplateTest extends TestCase {

	public static $prc;
	public static $tpl;
	public static $args;

	public static function setupBeforeClass() {
		self::$prc = new Template();
		self::$tpl = __DIR__ . '/index.php';
		self::$args = [
			'test_string' => 'Test String',
			'test_array' => [
				1,
				'a',
			],
		];
	}

	private function compare($rendered) {
		eval($rendered);
		$this->assertTrue(is_string($test_string));
		$this->assertTrue(is_array($test_array));
		$this->assertEquals(
			$test_string, self::$args['test_string']);
		$this->assertEquals(
			$test_array, self::$args['test_array']);
		$this->assertFalse(
			$test_array[0] === self::$args['test_array'][0]);
		$this->assertSame(
			intval($test_array[0]), self::$args['test_array'][0]);
		$this->assertSame(
			$test_array[1], self::$args['test_array'][1]);
	}

	public function test_constructor() {
		$prc = new Template();
		# the only method available
		# @todo Should we make this whole thing static?
		$this->assertTrue(
			method_exists($prc, 'load'));
	}

	public function test_print() {
		ob_start();
		self::$prc->load(self::$tpl, self::$args);
		$rv = ob_get_clean();
		$this->compare($rv);
	}

	public function test_buffered() {
		$rv = self::$prc->load(self::$tpl, self::$args, [], true);
		$this->compare($rv);
	}

	public function test_filtered() {
		$filter = new Filter();
		$rv = self::$prc->load(self::$tpl, self::$args, [
			[$filter, 'filter_string'],
			[$filter, 'filter_array'],
		], true);
		eval($rv);
		$this->assertTrue(is_string($test_string));
		$this->assertTrue(is_array($test_array));
		$this->assertEquals(
			$test_string, 'xx' . self::$args['test_string']);
		$this->assertSame(
			intval($test_array[0]), self::$args['test_array'][0] + 1);
		$this->assertSame(
			$test_array[1], 'yy' . self::$args['test_array'][1]);
	}

}

