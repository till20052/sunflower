<?php

class PagerClass
{
	private $list = 0;
	private $current = 0;
	private $per_page = 0;
	private $pages;
	private $total = 0;

	public function  __construct($list, $current, $per_page)
	{
		$this->list = $list;
		$this->current = $current > 1 ? $current : 1;
		$this->per_page = $per_page;
		$this->total = count($list);
		$this->pages = ceil($this->total/$per_page);
	}

	public function getUri($page)
	{
		$uri = preg_replace('/[?&]page=[0-9]+/', '', $_SERVER['REQUEST_URI']);
		$uri .= strpos($uri, '?') ? '&' : '?';
		$uri .= 'page=' . $page;

		return $uri;
	}

	public function getPrevious()
	{
		return $this->current > 1 ? $this->current - 1 : null;
	}

	public function getNext()
	{
		return $this->current < $this->pages ? $this->current + 1 : null;
	}

	public function getNextLink()
	{
		return $this->getUri($this->getNext());
	}

	public function getPreviousLink()
	{
		return $this->getUri($this->getPrevious());
	}

	public function getPage()
	{
		return $this->current;
	}

	public function getPages()
	{
		return $this->pages;
	}

	public function getTotal()
	{
		return $this->total;
	}

	public function getList()
	{
		return array_slice($this->list, ($this->current - 1) * $this->per_page, $this->per_page);
	}
}
