<?php defined('C5_EXECUTE') or die('Access Denied.');

class LocalizerHelper {

	public function getLocalizationFilename($locale) {
		$folder = defined('DIR_FILES_LOCALIZER') ? DIR_FILES_LOCALIZER : (DIR_BASE . '/files/localizer');
		return $folder . "/$locale.mo";
	}

	/** Returns the minimum concrete5 version that supports a translation context (or an empty string if it's still in pull-request state)
	* @param string $context
	* @return string
	*/
	public function getMinAppVersionForContext($context) {
		switch($context) {
			case 'AreaName':
			case 'GroupName':
			case 'GroupDescription':
			case 'GroupSetName':
			case 'SelectAttributeValue':
				return '5.6.2.2b2';
			default:
				return '5.6.2';
		}
	}

	private function getPackage() {
		static $pkg;
		if(!isset($pkg)) {
			$pkg = Package::getByHandle('localizer');
		}
		return $pkg;
	}

	public function getConfigured() {
		return ($this->getPackage()->config('configured') === 'yes') ? true : false;
	}
	public function setConfigured($configured) {
		if($configured) {
			$this->getPackage()->saveConfig('configured', 'yes');
		}
		else {
			$this->getPackage()->clearConfig('configured');
		}
	}

	public function getContextEnabled($context) {
		$vMin = $this->getMinAppVersionForContext($context);
		if((!strlen($vMin)) || (version_compare(APP_VERSION, $vMin) < 0)) {
			return false;
		}
		$value = $this->getPackage()->config("skipContext_$context");
		return ($value === 'yes') ? false : true;
	}
	public function setContextEnabled($context, $enabled) {
		$vMin = $this->getMinAppVersionForContext($context);
		if(strlen($vMin) && (version_compare(APP_VERSION, $vMin) >= 0)) {
			$key = "skipContext_$context";
			if($enabled) {
				$this->getPackage()->clearConfig($key);
			}
			else {
				$this->getPackage()->saveConfig($key, 'yes');
			}
		}
	}
}