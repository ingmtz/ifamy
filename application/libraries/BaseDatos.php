<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseDatos extends SQLite3{
	function __construct() {
		
	}
	public function escape($data) {
        if(is_array($data))
            return array_map("SQLite3::escapeString", $data);

        return SQLite3::escapeString($data);
    }
}
	

