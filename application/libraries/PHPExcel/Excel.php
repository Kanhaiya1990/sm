<?php
if(!defined('BASEPATH'))exit('No direct script access allowed');
require_once('PHPExcel.PHP');
class Excel extends PHPExcel
{
	public function __construct()
	{
		parent::__construct();
	}
}
?>