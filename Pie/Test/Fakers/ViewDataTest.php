<?php namespace Test\Fakers;

use Test\Build;
use Fakers\ViewData;
use Services\Authentication\Auth;
use Helpers\Dir;

class ViewDataTest extends Build
{
	use Dir;

	public function testGetMetaData()
	{
		$view = new ViewData();

		$this->assertStringEndsWith('/Pie/Views/Template/meta.php', $view->getMetaData());

		$this->assertFileExists($view->getMetaData());
	}

	public function testGetHomeLink()
	{
		$view = new ViewData();

		$this->assertEquals('/scores', $view->getHomeLink());		
	}

	public function testGetLogo()
	{
		$view = new ViewData();

		$this->assertEquals('/Pie/Public/Assets/Img/logo.png', $view->getLogo());

		$this->assertFileExists($this->getRootDirectory() . $view->getLogo());	
	}

	public function testFooterDate()
	{
		$view = new ViewData();

		$this->assertEquals(date('Y/m/d', time()), $view->getFooterDate());
	}
}