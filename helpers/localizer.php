<?php defined('C5_EXECUTE') or die('Access Denied.');

class LocalizerHelper
{

    public function getLocalizationFilename($locale)
    {
        $folder = defined('DIR_FILES_LOCALIZER') ? DIR_FILES_LOCALIZER : (DIR_BASE.'/files/localizer');

        return $folder."/$locale.mo";
    }

    private function getPackage()
    {
        static $pkg;
        if (!isset($pkg)) {
            $pkg = Package::getByHandle('localizer');
        }

        return $pkg;
    }

    public function getConfigured()
    {
        return ($this->getPackage()->config('configured') === 'yes') ? true : false;
    }
    public function setConfigured($configured)
    {
        if ($configured) {
            $this->getPackage()->saveConfig('configured', 'yes');
        } else {
            $this->getPackage()->clearConfig('configured');
        }
    }

    public function getParserEnabled($parserHandle)
    {
        $value = $this->getPackage()->config("skipDynamicItemsParser_$parserHandle");

        return ($value === 'yes') ? false : true;
    }
    public function setParserEnabled($parserHandle, $enabled)
    {
        $key = "skipDynamicItemsParser_$parserHandle";
        if ($enabled) {
            $this->getPackage()->clearConfig($key);
        } else {
            $this->getPackage()->saveConfig($key, 'yes');
        }
    }
}
