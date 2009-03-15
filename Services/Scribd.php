<?php
/**
 * Services_Scribd
 *
 * PHP version 5.2.0+
 *
 * @category 
 * @package 
 * @subpackage 
 * @author Rich Schumacher <rich.schu@gmail.com> 
 */

/**
 * Services_Scribd
 * 
 * @category 
 * @package 
 * @subpackage 
 * @author Rich Schumacher <rich.schu@gmail.com> 
 */
class Services_Scribd
{
    /**
     * URI of the API
     *
     * @var string
     */
    CONST API = 'http://api.scribd.com/api';

    /**
     * API key
     *
     * @var string
     */
    static public $apiKey = null;

    /**
     * Timeout to use when making the request
     *
     * @var integer
     */
    static public $timeout = 10;

    /**
     * The API session key
     *
     * @var string
     */
    public $apiSessionKey = null;

    /**
     * apiSignature 
     *
     * @var string
     */
    public $apiSignature = null;

    /**
     * An array that contains instances of the individual drivers
     *
     * @var array
     */
    private $drivers = array();

    /**
     * An array of drivers that we support
     *
     * @var array
     */
    private $validDrivers = array(
        'docs',
        'ext',
        'security',
        'user'
    );

    /**
     * __construct
     *
     * @param string $apiKey Our API key
     *
     * @return void
     */
    public function __construct($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * __get
     *
     * @param string $driver The driver we want to load
     *
     * @return Services_Scribd_Common
     */
    public function __get($driver)
    {
        if (!in_array($driver, $this->validDrivers)) {
            throw new Services_Scribd_Exception('Invalid driver provided');
        }

        if (empty($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->factory($driver);
        }

        return $this->drivers[$driver];
    }

    /**
     * factory
     * 
     * @param string $driver The driver we want to load
     *
     * @return Services_Scribd_Common
     */
    private function factory($driver)
    {
        $driver = mb_convert_case($driver, MB_CASE_TITLE);
        $file   = 'Services/Scribd/' . $driver . '.php';
        $class  = 'Services_Scribd_' . $driver;

        include_once $file;

        if (!class_exists($class)) {
            throw new Services_Scribd_Exception('not found');
        }

        return new $class();
    }
}

?>
