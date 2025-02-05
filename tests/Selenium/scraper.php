<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

require_once('vendor/autoload.php');


$host = 'http://192.168.80.1:4444';
$capabilities = DesiredCapabilities::chrome();
$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--headless']);
$capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);
$driver = RemoteWebDriver::create($host, $capabilities);
$driver->manage()->window()->maximize();


$driver->get('https://scrapingclub.com/exercise/list_infinite_scroll/');
$html = $driver->getPageSource();
echo $html;


$driver->close();