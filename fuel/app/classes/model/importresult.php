<?php

namespace Model;

class Importresult {
	public const COLOR_SUCCESS = "#0f04";
	public const COLOR_ERROR = "#f004";
	public const COLOR_WARNING = "#fa04";

	private $obj;
	private string $color;
	private string $msg;

	public function __construct($obj, string $color, string $msg) {
		$this->obj = $obj;
		$this->color = $color;
		$this->msg = $msg;
	}

	public function getObj() { return $this->obj; }
	public function getColor() { return $this->color; }
	public function getMsg() { return $this->msg; }
	
}