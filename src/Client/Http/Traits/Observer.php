<?php

namespace Unrlab\Client\Http\Traits;

use Unrlab\Client\Http\MonitoredResponse;

trait Observer
{

    /**
     * @param \Closure $closure
     * @return MonitoredResponse
     */
    public static function executeAndMonitor(\Closure $closure): MonitoredResponse
    {
        $startTime = microtime(true);
        $response = $closure();
        $endTime = microtime(true);
        $var = round((($endTime - $startTime) * 1000), 3);

        return new MonitoredResponse($response, $var);
    }
}