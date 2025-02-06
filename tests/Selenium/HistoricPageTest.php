<?php
namespace Selenium;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

class HistoricPageTest extends TestCase
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

    public function testHistoricPage()
    {
        $this->driver->get('http://localhost/path/to/historic.php');

        $pageTitle = $this->driver->getTitle();
        $this->assertEquals('Historique', $pageTitle, "Le titre de la page n'est pas celui attendu.");

        $navbar = $this->driver->findElement(WebDriverBy::cssSelector('nav'));
        $this->assertNotNull($navbar, "La barre de navigation n'est pas présente sur la page.");

        $timeline = $this->driver->findElement(WebDriverBy::cssSelector('.timeline'));
        $this->assertNotNull($timeline, "La timeline n'est pas présente sur la page.");

        $footer = $this->driver->findElement(WebDriverBy::cssSelector('footer'));
        $this->assertNotNull($footer, "Le pied de page n'est pas présent.");

        echo "Test de la page historic.php réussi.";
    }
}

//composer require php-webdriver/webdriver