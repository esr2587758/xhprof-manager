<?php
define('HTTP_HOST', $_SERVER['HTTP_HOST']);
//var_dump(HTTP_HOST);exit;


class Run {
	private $_action = "view";
	private $_filePath;
	
	public function dispatch() {
		$name = $_REQUEST['name'];
		$name = basename($name);
		$dir = dirname(__FILE__);
		$this->_filePath = $dir. '/data/' . $name;
		
		$_action = $_GET['action'];
		if(is_callable(array($this, $_action))){
			return call_user_func_array(array($this, $_action), array());
		}else{
			trigger_error('invalid parameters');
		}
	}
	
	public function view() {
		$filePath = $this->_filePath;
		//echo $filePath;exit;
		if(!file_exists($filePath)){
			echo "FILE NOT FOUND! <a href='/'>Back</a>";exit;
		}else{
			include $filePath;
		
			include_once "./xhprof_lib/utils/xhprof_lib.php";
			include_once "./xhprof_lib/utils/xhprof_runs.php";
			
			// save raw data for this profiler run using default
			// implementation of iXHProfRuns.
			$xhprof_runs = new XHProfRuns_Default();
			
			// save the run under a namespace "xhprof_foo"
			$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
			$url = "/xhprof_html/index.php?run={$run_id}&source=xhprof_foo";
			$url = 'http://'.HTTP_HOST.$url;
			$this->redirect($url);
		}
	}
	
	public function delete() {
		
		$filePath = $this->_filePath;
		if(!file_exists($filePath)){
			echo "FILE NOT FOUND! <a href='/'>Back</a>";exit;
		}else{
			unlink($filePath);
			$url = 'http://'.HTTP_HOST;
			$this->redirect($url);
		}
	}
	
	public function redirect($url) {
		header("Location: $url");exit;
	}
	
	public function upload() {
		$filePath = $this->_filePath;
		if(file_put_contents($filePath, $_POST['fileUpload'])){
			echo "upload is ok";
		}else{
			echo "upload failed";
		}
	}
}

$instance = new Run();
$instance->dispatch();



