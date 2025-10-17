<?php 
/**
* Created by @karebetconnec
*/
class Minifyscript extends MX_Controller
{
	
	function __construct()
	{
		# code...
	}
	public function index()
	{
		# code...
	}

	public function js() {
		$input = $this->input->get("src");
	    if(trim($input) === "") return $input;
	    $input = file_get_contents ($input,true);
	    echo preg_replace(
	        array(
	            // Remove comment(s)
	            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
	            // Remove white-space(s) outside the string and regex
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
	            // Remove the last semicolon
	            '#;+\}#',
	            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
	            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
	            // --ibid. From `foo['bar']` to `foo.bar`
	            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
	        ),
	        array(
	            '$1',
	            '$1$2',
	            '}',
	            '$1$3',
	            '$1.$3'
	        ),
	    $input);
	}
}

