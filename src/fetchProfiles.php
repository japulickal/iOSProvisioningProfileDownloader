<?php

/**
 * This is the main entry point of the application
 * Script need username and password for the apple portal as parameters
 * Usage
 * php fetchProgiles.php -u "username" -p "password" -t "teamId"
 *
 * PHP Version 5.2
 *
 * @category FrontController
 * @package  IOSProvisioningProfileDownloader
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

require_once dirname(__FILE__).'/Classes/Common/CommonExceptionAutoLoader.class.php';
require_once dirname(__FILE__).'/Classes/CommandLineController.class.php';

$objCommonExceptionAutoLoader = new CommonExceptionAutoLoader();

$objCommandLineController = new CommandLineController();
$objCommandLineController->execute();