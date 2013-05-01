<?php

class Helper
{
    /** @var \modX $modx An instance of the modX object */
    public $modx;
    /** @var array $bench An array of running benches */
    protected $bench = array();
    /** @var array $providers An array of available transport package providers */
    protected $providers = array();

    /**
     * Construct the object
     *
     * @param modX $modx A modX instance
     * @param array $options
     */
    public function __construct(modX &$modx, array $options = array())
    {
        $this->modx =& $modx;
    }

    /**
     * Formats the given file to be used as snippet/plugin content
     *
     * @param string $filename The path the to snippet file
     *
     * @return string The PHP content
     */
    public static function getPHPContent($filename)
    {
        $o = file_get_contents($filename);
        $o = str_replace('<?php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);

        return $o;
    }

    /**
     * Recursively unlink/rmdir the given folder
     *
     * @param string $dir The folder to empty
     *
     * @return void
     */
    public static function recursiveRmDir($dir)
    {
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if (is_dir($dir."/".$entry) === true){
                        self::recursiveRmDir($dir."/".$entry);
                    } else {
                        unlink($dir."/".$entry);
                    }
                }
            }
            closedir($handle);
            rmdir($dir);
        }
    }

    /**
     * Copy the appropriate license model to the right place
     *
     * @param array $sources An array of options defined in the build script
     * @param string $type The license type
     *
     * @return void
     */
    public static function setLicense($sources, $type)
    {
        $source = $sources['build'] . 'license/'. strtolower($type) .'.txt';
        $destination = $sources['docs'] . 'license.txt';
        copy($source, $destination);
    }

    /**
     * Format the given array of modAccessPolicy
     *
     * @param array $permissions
     *
     * @return string JSON encoded
     */
    public function buildPolicyFormatData(array $permissions)
    {
        $data = array();
        /** @var modAccessPolicy $permission */
        foreach ($permissions as $permission) {
            $data[$permission->get('name')] = true;
        }

        $data = json_encode($data);

        return $data;
    }

}
