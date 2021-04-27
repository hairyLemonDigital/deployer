<?php

namespace HairyLemonLtd\Deployer;

use Deployer\Exception\Exception;
use Illuminate\Support\Arr;
use Lorisleiva\LaravelDeployer\ConfigFileBuilder As LaravelDeployerConfigFileBuilder;
use HairyLemonLtd\Deployer\ConfigFile;

class ConfigFileBuilder extends LaravelDeployerConfigFileBuilder
{
    const DEFAULT_PHP_VERSION = '7.3';

    //change back to protected
    public $configs = [
        'default' => 'normal', // the hairylemon one
        'strategies' => [],
        'hooks' => [
            'start' => [],
            'build' => [],
            'ready' => [],
            'done' => [],
            'success' => [],
            'fail' => [],
            'rollback' => [],
        ],
        'options' => [
            'application' => "env('APP_NAME', 'Laravel')",
            'writable_mode' => 'chmod',
            'shared_dirs'   => [
                'storage',
                'public/assets'
            ],
            'writeable_dirs' => [
                'bootstrap/cache',
                'storage',
                'storage/app',
                'storage/app/public',
                'storage/framework',
                'storage/framework/cache',
                'storage/framework/sessions',
                'storage/framework/views',
                'storage/logs',
            ]
        ],
        'hosts' => [
            // there can only be one default!
            'master.cluster.16h.io' => [
                'deploy_path' => '/var/www/clients',
                'user' => 'hairylemon',
                'type'  => 'dev' // production
            ]
        ],
        'localhost' => [],
        'include' => [],
        'custom_deployer_file' => false,
    ];

    private $host;


    public function __construct()
    {
        parent::__construct(); // loads config above
    }

    /**
     * Build a config file object based on the information
     * collected so far.
     *
     * @return \HairyLemonLtd\Deployer\ConfigFile
     */
    public function build(): \HairyLemonLtd\Deployer\ConfigFile
    {
        return new ConfigFile($this->configs);
    }

    public function getHostname()
    {
        if($this->host ?? false){
            return $this->host;
        }

        return array_search(head($this->configs['hosts']), $this->configs['hosts']);
    }

    /**
     * Return current host configurations at the given key.
     *
     * @return mixed
     */
    public function getHost($key)
    {
        if(! $this->host ){
            echo "Please set the hostname before getting host values\n\n";
            return null;
        }
        return Arr::get($this->configs['hosts'][$this->host], $key);
    }

    /**
     * Update the host configurations with the given key/value pair.
     * @return self
     */
    public function setHost($key, $value)
    {
        $hostname = $this->getHostname();

        if ($key !== 'name') {
            $this->configs['hosts'][$hostname][$key] = $value;

            return $this;
        }

        return $this;
    }

    public function setHostname($value)
    {
        if (! $this->host) {
            $default_hostname = $this->getHostname();
        }

        echo "default hostname: ".$default_hostname."\n";

        if ($value !== $default_hostname) {
            // make a hosts item with new host value from the default one
            $this->configs['hosts'][$value] = $this->configs['hosts'][$default_hostname];
            // remove the default one
            unset($this->configs['hosts'][$default_hostname]);
        }

        $this->host = $value;

        return $this;
    }
}
