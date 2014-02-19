<?php  

/**
 * 
include 'xhprof.php';
xhprof::start();
register_shutdown_function('shutdown_xhprof');
function shutdown_xhprof() {
	xhprof::end();
}
 * 
 * 
 */

	/**
	 * xhprof 调试工具类
	 * 
	 * @author huangqing
	 */
	class xhprof {
		private static $_config = array(
			'storeType' => 'remote',// local or remote
			'dataFileFormat' => 'Y-m-d H:i:s',
			'dataFilePrefix' => 'PMS',
		);
		
		private static $_localConfig = array(
			'dataRoot' => '/www/xhprof-manager/data/',
		);
		
		
		public static function start($config = array()) {
			self::$_config = array_merge(self::$_config, $config);
			xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
		}
		
		public static function end() {
			$xhprof_data = xhprof_disable();
			$storeFuncName = self::$_config['storeType'].'Store';
			self::$storeFuncName($xhprof_data);
		}
		
		private static function localStore($xhprof_data) {
			$dataPath = self::$_localConfig['dataRoot'];
			$filePrefix = self::$_config['dataFilePrefix'];
			$fileName = $filePrefix.'.'.date(self::$_config['dataFileFormat']);
			$filePath = $dataPath.$fileName.'.php';
			//echo "<a href='http://360.xhprof.patsnap.com/index.php?file_number=".$filePath."'>".$filePath."</a>";
			file_put_contents($filePath, '<?php $xhprof_data ='.var_export($xhprof_data, true).';?>');
		}
		
		private static function remoteStore($xhprof_data) {
			$url = "http://xhprof.patsnap.com/run.php?action=upload";
			$ch = curl_init($url);
			$filePrefix = self::$_config['dataFilePrefix'];
			$fileName = $filePrefix.'.'.date(self::$_config['dataFileFormat']).'.php';
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'name' => $fileName,
				'fileUpload' => '<?php $xhprof_data ='.var_export($xhprof_data, true).';?>'
			));
			 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result;
		}
		
		
	}
	
?>