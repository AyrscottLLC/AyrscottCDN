<?php

require_once __DIR__ . '/bunnycdn-storage.php'; // https://raw.githubusercontent.com/BunnyWay/BunnyCDN.PHP.Storage/master/bunnycdn-storage.php

// This class REQUIRES and extends BunnyCDNStorage to use environment variables 
// provided by the application runtime (i.e., Heroku) or environment itself (bash/windows)
// to authenticate with their API and define the base storage zone simplifying use
// throughout our own applications.
// 
// Environment variables that should be provided: 
// 
// Variable                             Sample Value
// ----------------------------------------------
// BUNNY_STORAGE_ZONE                   some-cdn-zone
// BUNNY_STORAGE_REGION                 ny
// BUNNY_API_KEY                        some-random-password
// BUNNY_STORAGE_BASE_PATH (optional)   /client
// 
// ---------------------------------------------------------------
// 
// The above variables should be accessible in the $_ENV super global.
// 
// $_ENV['BUNNY_STORAGE_ZONE'];
// $_ENV['BUNNY_STORAGE_REGION'];
// $_ENV['BUNNY_API_KEY'];
// $_ENV['BUNNY_STORAGE_BASE_PATH'];
// 
// Overloaded functions and their documentation can be found in the class below

class AyrscottCDN extends BunnyCDNStorage {
    /**
     * Create a BunnyCDNStorage object initialized with environment variables.
     */
    public function __construct()
    {
        parent::__construct($_ENV['BUNNY_STORAGE_ZONE'], $_ENV['BUNNY_API_KEY'], $_ENV['BUNNY_STORAGE_REGION']);
    }

    /** 
     * Return the settings specified by the environment variables.
     */ 
    public function getSettings() {
        // todo return the optional base path
        return [
            'BUNNY_STORAGE_ZONE' => $_ENV['BUNNY_STORAGE_ZONE'],
            'BUNNY_STORAGE_REGION' => $_ENV['BUNNY_STORAGE_REGION'],
            'BUNNY_API_KEY' => $_ENV['BUNNY_API_KEY'],
        ];
    }

    /**
     * Simplifies the call to the BunnyCDNStorage object's call by pre-pending the zone and optional base path.
     *
     * @param String $sub_path The path you wish to the storage object of. Must start with a /
     * @return Array Contains a list of storage objects.
     **/
    public function getStorageObjects($sub_path)
    {
        if(isset($_ENV['BUNNY_STORAGE_BASE_PATH'])) {
            $full_path = "/" . $_ENV['BUNNY_STORAGE_ZONE'] . $_ENV['BUNNY_STORAGE_BASE_PATH'] . $sub_path;
        } else {
            $full_path = "/" . $_ENV['BUNNY_STORAGE_ZONE'] . $sub_path;
        }

        return parent::getStorageObjects($full_path);
    }
}
