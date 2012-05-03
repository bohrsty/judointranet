<?php


/**
 * class HtmlTemplate implements the handling of the templates
 */
class HtmlTemplate extends Object {
	
	/*
	 * class-variables
	 */
	private $template_file;
	private $template_html;
	
	/*
	 * getter/setter
	 */
	public function get_template_file(){
		return $this->template_file;
	}
	public function set_template_file($template_file) {
		$this->template_file = $template_file;
	}
	public function get_template_html(){
		return $this->template_html;
	}
	public function set_template_html($template_html) {
		$this->template_html = $template_html;
	}
	
	/*
	 * constructor/destructor
	 */
	public function __construct($template_file) {
		
		// set class-variables
		$this->set_template_file($template_file);
		
		// read file
		$retval = $this->read_template_file();
		if($retval == -1) {
			$errno = $GLOBALS['Error']->error_raised('ReadTemplateFile','fopen failed: '.$template_file);
			throw new Exception('ReadTemplateFile',$errno);
		} elseif($retval == -2) {
			$errno = $GLOBALS['Error']->error_raised('ReadTemplateFile','fread failed: '.$template_file);
			throw new Exception('ReadTemplateFile',$errno);
		}
	}
	
	/*
	 * methods
	 */
	/**
	 * to_html returns the template as html-string
	 * 
	 * @return string template as complete html-string
	 */
	public function to_html() {
		
		// return html-string
		return $this->get_template_html();
	}
	
	
	
	
	
	
	
	
	
	/**
	 * read_template_file reads the content of the template_file and stores it in
	 * $this->template_html
	 * 
	 * @return mixed 0 if success, -1 fopen failed, -2 fread failed
	 */
	private function read_template_file() {
		
		// read file-content
		// get filehandle for reading
		$fh = @fopen($this->get_template_file(),'r');
		// check handle
		if(!$fh) {
			return -1;
		} else {
			
			// read content
			$content = @fread($fh,filesize($this->get_template_file()));
			// check content
			if(!$content) {
				return -2;
			} else {
				$this->set_template_html($content);
				return 0;
			}
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * parse returns the template as html-string parsed with the given content
	 * 
	 * @param array $contents content to be insert into the template
	 * @param int $count parse the template $count times
	 * @return string template parsed with content as complete html-string
	 */
	public function parse($contents,$count = 1) {
		
		// get template
		$template = $this->get_template_html();
		
		// parse $count times
		for($i = 0;$i < count($count);$i++) {
			
			// walk through content-array
			foreach($contents as $name => $content) {
				
				// replace marker in template
				$template = str_replace('###'.$name.'###',$content,$template);
			}
		}
		
		// return parsed template
		return $template;
	}
	
	
}



?>
