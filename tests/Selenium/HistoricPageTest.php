<?php
namespace Selenium;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

class HistoricPageTest extends TestCase
{
    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    protected function setUp(): void
    {
        // Adresse du serveur Selenium
        $host = 'http://192.168.1.131:4444';
        $capabilities = DesiredCapabilities::chrome();
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']); // Mode sans interface graphique
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
        $url = 'http://localhost/ProJetProdDev/app/Views/History/historic.php';
        $this->driver->get($url);

        // ✅ Vérification du titre de la page
        $pageTitle = $this->driver->getTitle();
        $this->assertEquals('Historique', $pageTitle, "Le titre de la page n'est pas celui attendu.");

        echo "\n✅ Test de la page historic.php réussi.\n";
    }
    private function assertElementExists(WebDriverBy $selector, string $errorMessage)
    {
        $elements = $this->driver->findElements($selector);
        $this->assertGreaterThan(0, count($elements), $errorMessage);
    }
}
