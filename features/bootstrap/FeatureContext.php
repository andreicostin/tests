<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends Behat\MinkExtension\Context\MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @When /^I search for "([^"]*)"$/
     */
    public function iSearchFor($searchTerm)
    {
        $this->getSession()->wait(5000); 
        $page = $this->getSession()->getPage();
        $searchBox = $page->find('xpath', '//input[@class="search-box"]');
        $searchButton = $page->find('xpath','//a[@class="go"]/span');
        if($searchBox == null) {
            throw new \Exception('Search box is not found');
        }
        if(empty($searchButton)) {
            throw new \Exception('Go button is not found');
        }
        $searchBox->setValue($searchTerm);
        $searchButton->click();
    }

    /**
     * @Then /^I should see some results$/
     */
    public function iShouldSeeSomeResults()
    {
        $this->getSession()->wait(10000);
        $results = $this->getSession()->getPage()->findAll('xpath', '//*[@id="productlist-results"]/div/div[4]/ul/li[1]');
        if (count($results) < 1) {
            throw new Exception("No search results found");
        }
    }

     /**
     * @When /^I organize them in (\d+) columns$/
     */
    public function iOrganizeThemInColumns($colNumber)
    {
        $this->getSession()->wait(4000);
        $page = $this->getSession()->getPage();
        $threeColumns = $page->find('xpath', '//p[@class="display-options"]/a[contains(@class, "large")]');
        $fourColumns = $page->find('xpath', '//p[@class="display-options"]/a[contains(@class, "regular")]');
        if( $colNumber === '3') {
            if(null === $threeColumns) {
                throw new \LogicException('Could not found the ' . $colNumber . ' columns button');
            } else {
                $threeColumns->click();
            }
        } else if ($colNumber === '4') {
            if(null === $fourColumns) {
                throw new \LogicException('Could not found the ' . $colNumber . ' columns button');
            } else {
                $fourColumns->click();
            }
        }
    }

    /**
     * @Then /^I should see some results organized in (\d+) columns$/
     */
    public function iShouldSeeSomeResultsOrganizedInColumns($colNumber)
    {
        $this->getSession()->wait(2000);
        $page = $this->getSession()->getPage();
        $resultsThreeGrid = $page->find('xpath', '//div[@class="product-list"]/div[contains(@class, "three-grid")]');
        $resultsFourGrid = $page->find('xpath', '//div[@class="product-list"]/div[contains(@class, "four-grid")]');
        if ($colNumber === '3') {
            if (null === $resultsThreeGrid) {
                throw new \LogicException('The results are not displayed as ' . $colNumber . ' columns');
            } 
        } else if ($colNumber === '4') {
            if (null === $resultsFourGrid) {
                throw new \LogicException('The results are not displayed as ' . $colNumber . ' columns');
            }
        }
    }

    /**
     * @When /^I select "([^"]*)" as "([^"]*)"$/
     */
    public function iSelectAs($value, $filter)
    {
        $page = $this->getSession()->getPage();

        $this->getSession()->wait(8000); 
        $filter = strtolower($filter);

        switch ($filter) {
            case 'gender':
                $filterId = 'floor';
                break;
            case 'product type':
                $filterId = 'attribute_900';
                break;
            case 'style':
                $filterId = 'attribute_989';
                break;
            case 'colour':
                $filterId = 'base_colour';
                break;
            default:
                $filterId = $filter;
        }

        $filter_xpath = '//div[@data-id="' . $filterId . '"]';
        $filter_element =  $page->find('xpath', $filter_xpath);

        if ($filter_element === null) {
            throw new Exception("'$filter' filter type element not found");
        }

        $value = ucfirst($value);
        if ($filter === 'gender' || $filter === 'size') {
            $value = strtoupper($value);
        }

        $value_xpath = '/div/ul/li[@data-name="' . $value . '"]/a/span[@class="facetvalue-name"]';
        $value_element = $filter_element->find('xpath', $value_xpath);

        if ($value_element === null) {
            throw new Exception("'$value' option not found (under the '$filter' filter)");
        }

        $value_element->click();

        $this->getSession()->wait(3000);
    }

    /**
     * @When /^I open the currency menu$/
     */
    public function iOpenTheCurrencyMenu()
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(3000);
        $localizationMenu = $page->find('xpath', '//div[@id="localisationMenu"]');
        $currencyButton = $localizationMenu->find('xpath', '/a[@class="currency-locale-link"]/span[@class="menu-arrow"]');
        $currencyMenu = $localizationMenu->find('xpath', '/div[@class="menu"]');
        $currencyButton->click();
        $this->getSession()->wait(1000);
        if ($currencyMenu->isVisible() === false) {
            throw new Exception("The currency menu didn't open");
        }
    }

    /**
     * @Given /^I change currency to "([^"]*)"$/
     */
    public function iChangeCurrencyTo($currency)
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(1000);
        $currencyList = $page->find('xpath', '//select[@id="currencyList"]');
        $selectCurrency = $currencyList->find('xpath', '/option[@data-label="'. $currency .'"]');

        if (null === $selectCurrency) {
            throw new Exception("The currency doesn't exist!");
        }

        $currencyList->selectOption($currency);
    }

    /**
     * @Then /^I should see selected currency to "([^"]*)"$/
     */
    public function iShouldSeeSelectedCurrencyTo($currency)
    {
        $this->getSession()->wait(10000);
        $page = $this->getSession()->getPage();
        $selectedCurrency = $page->find('xpath', '//*[@class="selected-currency" and contains(text(),"'. $currency .'")]'); 
        if (null === $selectedCurrency) {
            throw new Exception("The " . $currency . " currency wasn't selected!");
        }
       // throw new PendingException();
    }
    
     /**
     * @Given /^I visit the first product$/
     */
    public function iVisitTheFirstProduct()
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(5000);
        $firstProduct = $page->find('xpath', '//div[contains(@class, "results")]/ul/li[1]/a');
        $href = $firstProduct->getAttribute('href');
        if(null == $firstProduct) {
            throw new Exception("There is no product in the result list");
        }
        $firstProduct->click();
        
    }

    /**
     * @Given /^I click on Save for Later$/
     */
    public function iClickOnSaveForLater()
    {
        $this->getSession()->wait(3000);
        $page = $this->getSession()->getPage();
        $saveForLaterButton = $page->find('xpath', '//div[@id="product-save"]/div/a[contains(@class, "save-button-link")]/span[@class="heartSecondary"]');
        if(null == $saveForLaterButton) {
            throw new Exception("Save for later button doesn't exist");
        }
        $saveForLaterButton->click();
    }

    /**
     * @Then /^I should see the product saved for later$/
     */
    public function iShouldSeeTheProductSavedForLater()
    {
        $this->getSession()->wait(3000);
        $page = $this->getSession()->getPage();
        $goToSaveForLater = $page->find('xpath','//a[@class="saved-items"]');
        $goToSaveForLater->click();
        $this->getSession()->wait(5000);
        $saveForLaterCount = $page->find('xpath','//span[@id="ctl00_ContentMainPage_ItemCount"]');
        if($saveForLaterCount->getText() !== '1') {
            throw new Exception("The item was not saved for later");
        }
    }

}

