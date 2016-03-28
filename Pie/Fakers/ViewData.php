<?php namespace Fakers;

use Services\Config\Loader;
use Services\Authentication\Auth;
use Fakers\Menu;
use Fakers\LinkMenu;
use Fakers\AccountForm;
use Fakers\HiddenFields;
use Fakers\Links;
use Carbon\Carbon;
use Services\Html\Lists as MainMenu;
use Helpers\Dir;
use Fakers\Admin;

class ViewData
{
	use Dir;

	protected $metaData;

	protected $headerUrl;

	protected $footerUrl;

	protected $homeLink;

	protected $menu;

	protected $headerInputs;

	protected $accountForm;

	protected $logo;

	protected $footerDate;

	protected $linksMenu;

	protected $config;

	protected $admin;

	public function __construct($admin = false)
	{
		$this->config = new Loader();
		$this->auth = new Auth();

		$this->setStandardData();

		$this->admin = $admin;
	} 

	protected function setStandardData()
	{
		$this->metaData = $this->getRootDirectory() . $this->config->get('view.metaDataUrl');
		$this->headerUrl = $this->getRootDirectory() . $this->config->get('view.headerUrl');
		$this->footerUrl = $this->getRootDirectory() . $this->config->get('view.footerUrl');
		$this->homeLink = $this->config->get('view.homeLink');
		$this->logo = $this->config->get('view.logo');
	}

	public function setAdmin($state)
	{
		$this->admin = $state;
	}

	public function getMetaData()
	{
		return $this->metaData;
	}

	public function getHeaderUrl()
	{
		return $this->headerUrl;
	}

	public function getFooterUrl()
	{
		return $this->footerUrl;
	}

	public function getHomeLink()
	{
		return $this->homeLink;
	}

	public function getLogo()
	{
		return $this->logo;
	}

	public function getMenu()
	{
		$menu = new Menu($this->auth, new MainMenu(), $this->admin);

		return $menu->make();
	}

	public function getAccountForm()
	{
		return $this->admin ? false : $this->buildAccountForm();
	}

	protected function buildAccountForm()
	{
		$form = new AccountForm();

		return $form->make();
	}

	public function getHiddenFields()
	{
		$fields = new HiddenFields();

		return $fields->make();
	}

	public function getFooterDate()
	{
		$dt = Carbon::now();
		return $dt->format('Y/m/d');
	}

	public function getLinksMenu()
	{
		$linkMenu = new LinkMenu($this->auth, new MainMenu());

		return $linkMenu->make();
	} 
}