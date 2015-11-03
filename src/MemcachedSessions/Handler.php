<?php

class MemcachedSessions_Handler implements Zend_Session_SaveHandler_Interface
{
    private $_lifetime = "1800";
    private $_automatic_serialization = true;
    private $_host = '127.0.0.1';
    private $_port = "11211";
    private $_persistent = true;
    private $_compression = true;

    public $cache;

    public function __construct($config)
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        } else if (!is_array($config)) {
            /**
             * @see Zend_Session_SaveHandler_Exception
             */
            require_once 'Zend/Session/SaveHandler/Exception.php';

            throw new Zend_Session_SaveHandler_Exception(
                '$config must be an instance of Zend_Config or array of key/value pairs containing '
                . 'configuration options for Zend_Session_SaveHandler_DbTable and Zend_Db_Table_Abstract.');
        }

        foreach ($config as $key => $value) {
            if (property_exists($this, '_' . $key)) {
                $this->{'_' . $key} = $value;
            } else {
                throw new Zend_Session_SaveHandler_Exception('Invalid config: ' . $key);
            }
        }

        $frontendOptions = array(
            'lifetime'                => $this->_lifetime,
            'automatic_serialization' => $this->_automatic_serialization,
        );
        $backendOptions = array(
            'compression' => $this->_compression,
            'host'        => $this->_host,
            'port'        => $this->_port,
            'persistent'  => $this->_persistent,
        );

        $this->cache = Zend_Cache::factory('Core', 'Memcached', $frontendOptions, $backendOptions);
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        if (!($data = $this->cache->load($id))) {
            return '';
        } else {
            return $data;
        }
    }

    public function write($id, $sessionData)
    {
        $this->cache->save($sessionData, $id, array(), $this->_lifetime);

        return true;
    }

    public function destroy($id)
    {
        $this->cache->remove($id);

        return true;
    }

    public function gc($notusedformemcache)
    {
        return true;
    }
}