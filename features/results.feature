Feature: Use the website to change how search results are displayed
    So that I can select my yellow t shirts
    As an Australian customer
    I want to be able to reorganize how they are displayed

Scenario: Display search results in 4 columns
    Given I am on "/au/"
    When I search for "yellow t shirts"
    #Given I have searched for  on the Australian store
    And I should see some results
    #And I have some yellow t shirts displayed
    When I organize them in 4 columns
    Then I should see some results organized in 4 columns
