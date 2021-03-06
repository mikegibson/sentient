<?php

namespace Sentient\Pages;

use Sentient\Data\ManagedRepositoryInterface;
use Sentient\Node\ControllerNode;
use Sentient\Node\ControllerNodeInterface;
use Symfony\Component\HttpFoundation\Request;

class PageNode extends ControllerNode {

	private $repository;

	private $page;

	private $initialized = false;

	public function __construct(
		ManagedRepositoryInterface $repository,
		Page $page = null,
		ControllerNodeInterface $parentNode = null
	) {
		$this->repository = $repository;
		$this->page = $page;
		$this->parentNode = $parentNode;
	}

	protected function getRepository() {
		return $this->repository;
	}

	public function getPage() {
		return $this->page;
	}

	protected function getChildPages(array $criteria = []) {
		$criteria['parent'] = $this->getPageId();
		return $this->getRepository()->findBy($criteria);
	}

	protected function getChildPage($slug) {
		$result = $this->getChildPages(['slug' => $slug]);
		return isset($result[0]) ? $result[0] : null;
	}

	public function getChild($key) {
		if(!isset($this->childNodes[$key])) {
			$page = $this->getChildPage($key);
			if($page instanceof Page) {
				$this->initializeChildPage($page);
			}
		}
		return parent::getChild($key);
	}

	public function getChildren() {
		if(!$this->initialized) {
			foreach($this->getChildPages() as $page) {
				$this->initializeChildPage($page);
			}
			$this->initialized = true;
		}
		return parent::getChildren();
	}

	protected function initializeChildPage(Page $page) {
		if(isset($this->childNodes[$page->getSlug()])) {
			return;
		}
		$node = new PageNode($this->getRepository(), $page, $this);
		$this->registerChild($node);
	}

	public function getRouteName() {
		return 'pages';
	}

	public function getActionName() {
		return 'view';
	}

	public function getActionLabel() {
		return 'View page';
	}

	public function getName() {
		$page = $this->getPage();
		if($page === null) {
			return $this->getRouteName();
		}
		return $page->getSlug();
	}

	public function getLabel() {
		$page = $this->getPage();
		if($page === null) {
			return 'Pages';
		}
		return $page->getTitle();
	}

	protected function getPageId() {
		$page = $this->getPage();
		if($page === null) {
			return null;
		}
		return $page->getId();
	}

	public function getTemplate() {
		return '@pages/view';
	}

	public function isAccessible() {
		return !!$this->getPage();
	}

	public function isVisible() {
		return !!$this->getPage();
	}

	public function invoke(Request $request) {
		return ['page' => $this->getPage()];
	}

}