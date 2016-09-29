Feature: Use the website to change currency
	As an Australian customer
	I want to be able to see prices in New Zeeland dollars
Scenario: Change currency to New Zeeland dollars
	Given I am on "/au/"
	When I open the currency menu
    And I change currency to "NZD"
	Then I should see selected currency to "NZD"