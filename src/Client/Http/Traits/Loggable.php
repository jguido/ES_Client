<?php

namespace Unrlab\Client\Http\Traits;

use Psr\Log\LoggerInterface;

trait Loggable
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    protected function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    protected function logError($message)
    {
        $this->logger->error($message);
    }

    protected function logNotice($message) {
        $this->logger->notice($message);
    }

}
