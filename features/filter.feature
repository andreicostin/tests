Feature: Use the website to refine search results using the filters
	So that I can select my t shirts
	As a customer
	I want to be able to filter them by gender, size or colour
Scenario: Filter by gender or size or colour
	Given I am on the homepage
	And I search for "t shirts"
	When I select "women" as "gender"
    And I select "yellow" as "colour"
    And I select "UK 6" as "size"
	Then I should see some results