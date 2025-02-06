<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

require_once('vendor/autoload.php');

// the URL to the local Selenium Server
$host = 'http://localhost:4444/';

// to control a Chrome instance
$capabilities = DesiredCapabilities::chrome();

// define the browser options
$chromeOptions = new ChromeOptions();
// to run Chrome in headless mode
$chromeOptions->addArguments(['--headless']); // <- comment out for testing

// register the Chrome options
$capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

// initialize a driver to control a Chrome instance
$driver = RemoteWebDriver::create($host, $capabilities);

// maximize the window to avoid responsive rendering
$driver->manage()->window()->maximize();

// open the target page in a new tab
$driver->get('https://scrapingclub.com/exercise/list_infinite_scroll/');

// extract the HTML page source and print it
$html = $driver->getPageSource();
echo $html;

// close the driver and release its resources
$driver->close();
