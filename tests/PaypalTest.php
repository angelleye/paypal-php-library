<?php namespace Paypal\Tests;

use Mockery as m;
use \PHPUnit_Framework_TestCase;
use \Paypal\Paypal;
use \Paypal\PayPal_Adaptive;

class PaypalTest extends PHPUnit_Framework_TestCase {
	
	public function setUp() {
		$_SERVER['SERVER_PORT'] = 1;
		$_SERVER['REMOTE_ADDR'] = '';
	}
	
	/**
	 * Super simple test as example to write further tests
	 */
	public function testInstance() {
		$this->assertInstanceOf('\Paypal\PayPal_Adaptive', new PayPal_Adaptive(array()));
		$this->assertInstanceOf('\Paypal\Paypal', new Paypal(array()));
	}
	
}
