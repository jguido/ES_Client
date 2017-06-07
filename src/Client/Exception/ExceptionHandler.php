<?php

namespace Unrlab\Client\Exception;

use GuzzleHttp\Exception\BadResponseException;

trait ExceptionHandler
{
    protected static $exceptionStack = [];

    protected static function registerDocumentAlreadyExistsExceptionException()
    {
        return static::resolveExceptionFromName(__FUNCTION__, 400);
    }

    protected static function registerDocumentNotFoundException()
    {
        return static::resolveExceptionFromName(__FUNCTION__, 404);
    }

    protected static function registerIndexNotFoundException()
    {
        return static::resolveExceptionFromName(__FUNCTION__, 404);
    }

    protected static function registerIndexAlreadyExistsException()
    {
        return static::resolveExceptionFromName(__FUNCTION__, 400);
    }

    /**
     * @param BadResponseException $eexception
     * @return \Exception
     */
    protected static function getExceptionFromCode(BadResponseException $eexception): \Exception
    {
        if (!isset(static::$exceptionStack[$eexception->getCode()])) {
            return new UndefinedClientException($eexception->getMessage());
        }
        $exception = static::$exceptionStack[$eexception->getCode()];

        $exceptionClass = $exception($eexception->getResponse()->getBody()->getContents());
        static::clearExceptionStack();
        return $exceptionClass;
    }


    /**
     * @return void
     */
    private static function clearExceptionStack()
    {
        static::$exceptionStack = [];
    }

    /**
     * @param $functionName
     * @param $errorCode
     * @return \Closure
     */
    private static function resolveExceptionFromName($functionName, $errorCode): \Closure
    {
        $exceptionName = __NAMESPACE__ . preg_replace('/Register/', '\\', $functionName, 1);
        return self::prepareException($errorCode, $exceptionName);
    }

    /**
     * @param $functionName
     * @param $namespace
     * @param $errorCode
     * @return \Closure
     */
    private static function resolveExceptionFromNameInSpecificNamespace($functionName, $namespace, $errorCode): \Closure
    {
        $exceptionName = __NAMESPACE__ . preg_replace('/Register/', '\\' . $namespace . '\\', $functionName, 1);
        return self::prepareException($errorCode, $exceptionName);
    }

    /**
     * @param $errorCode
     * @param $exceptionName
     * @return \Closure
     */
    private static function prepareException($errorCode, $exceptionName): \Closure
    {
        return static::$exceptionStack[$errorCode] = function ($message) use ($exceptionName) {
            return new $exceptionName($message);
        };
    }


}
