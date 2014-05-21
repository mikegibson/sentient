<?php

namespace Layer\Data\Paginator;

/**
 * Class Paginator
 *
 * @package Controller\Paginator
 */
abstract class Paginator implements PaginatorInterface {

	/**
	 * @var \Layer\Data\Paginator\PaginatorRequestInterface
	 */
	protected $request;

	/**
	 * @var PaginatorResultInterface
	 */
	protected $result;

	/**
	 * @var int
	 */
	protected $defaultPerPage = 10;

	/**
	 * @var int
	 */
	protected $maxPerPage = 100;

	/**
	 * @param PaginatorRequestInterface $request
	 * @param PaginatorResultInterface $result
	 */
	public function __construct(
		PaginatorResultInterface $result,
		PaginatorRequestInterface $request
	) {
		$this->request = $request;
		$this->result = $result;
	}

	/**
	 * @return array
	 */
	public function getColumns() {

		return $this->result->getColumns();
	}

	/**
	 * @return array
	 */
	public function getData() {

		return $this->result->getData($this->getCurrentPage(), $this->getPerPage(), $this->getSortKey(), $this->getDirection());
	}

	/**
	 * @return int
	 */
	public function getCurrentPage() {

		if (!isset($this->_vars['page'])) {
			$this->_vars['page'] = $this->request->getCurrentPage();
		}

		return $this->_vars['page'];
	}

	/**
	 * @return int
	 */
	public function getPerPage() {

		return min((int) $this->request->getPerPage() ?: $this->defaultPerPage, $this->maxPerPage);
	}

	/**
	 * @return string|null
	 */
	public function getSortKey() {

		return $this->request->getSortKey();
	}

	/**
	 * @return string|null
	 */
	public function getDirection() {

		return $this->request->getDirection();
	}

	/**
	 * @return int
	 */
	public function getTotal() {

		return $this->result->getCount();
	}

	/**
	 * @return int
	 */
	public function getPageCount() {
		return (int)ceil($this->getTotal() / $this->getPerPage());
	}

	/**
	 * @return bool
	 */
	public function hasNext() {
		return ($this->getPageCount() > $this->getCurrentPage());
	}

	/**
	 * @return bool
	 */
	public function hasPrev() {
		return ($this->getCurrentPage() > 1);
	}

	/**
	 * @param $page
	 * @return bool
	 */
	public function hasPage($page) {
		return ($this->getPageCount() >= (int)$page);
	}

	/**
	 * @return PaginatorRequestInterface
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @return PaginatorResultInterface
	 */
	public function getResult() {
		return $this->result;
	}

}