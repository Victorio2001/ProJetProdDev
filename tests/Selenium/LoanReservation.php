<?php
// tests/PageTitleTest.php

namespace Selenium;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class LoanReservation extends TestCase
{
    /**
     * @var RemoteWebDriver
     */
    protected $driver;


    protected function setUp(): void
    {
        $host = 'http://192.168.80.1:4444';

        $capabilities = DesiredCapabilities::chrome();
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']);
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);

        $this->driver = RemoteWebDriver::create($host, $capabilities);
        $this->driver->manage()->window()->maximize();
    }

    protected function tearDown(): void
    {
        if ($this->driver !== null) {
            $this->driver->quit();
        }
    }

    public function testPageTitle()
    {
        $this->driver->get('https://www.pokebip.com/');

        $pageTitle = $this->driver->getTitle();
        echo "coolos";

        $expectedTitle = 'Pokémon : news, astuces, soluces, pokédex, communauté - Pokébip.com';

        $this->assertEquals($expectedTitle, $pageTitle, "Le titre de la page n'est pas celui attendu.");
    }
}
