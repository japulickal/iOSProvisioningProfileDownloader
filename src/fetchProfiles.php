<?php

// This is the main entry point of the application
// Script need username and password for the apple portal as parameters
// Usage
// php fetchProgiles.php -u "username" -p "password" -t "teamId"
// 
// @author Jose Antony <jose@joseantony.com>

require_once 'Classes/CommandLineController.class.php';

$objCommandLineController = new CommandLineController();
$objCommandLineController->execute();