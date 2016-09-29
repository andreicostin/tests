Feature: Use the website to save item for later
	As a customer
	I want to be able to save an item for later
Scenario: Save an item for later
	Given I am on the homepage
	When I search for "hats"
    And I visit the first product
    And I click on Save for Later
    Then I should see the product saved for later
