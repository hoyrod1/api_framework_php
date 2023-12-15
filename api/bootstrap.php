<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Required functionality for the index page
 * 
 * @category Php_API
 * @package  Vendor-Composer-.env-src-error-handler-header-content-type
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks.com/api/bootstrap.com
 */

require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("ErrorHandler::handleErrors");
set_exception_handler("ErrorHandler::handleException");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-Type: application/json; charset=UTF-8");