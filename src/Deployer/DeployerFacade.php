<?php

namespace HairyLemonLtd\Deployer;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HairyLemonLtd\Deployer\Skeleton\SkeletonClass
 */
class DeployerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'deployer';
    }
}
