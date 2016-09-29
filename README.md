# tests

How to run the tests:

1. Initialise the environment

    - $ php composer.phar install
    - $ vendor/bin/behat --init
    - $ java -jar selenium-server-standalone-2.37.0.jar chromedriver  (wait for this to load - about 1 minute - leave the terminal tab running);

2. Run the Scenarios in a different terminal tab:

    1. $ vendor/bin/behat features/search.feature
    2. $ vendor/bin/behat features/search.au.feature 
    3. $ vendor/bin/behat features/results.feature 
    4. $ vendor/bin/behat features/filter.feature 
    5. $ vendor/bin/behat features/currency.feature 
    6. $ vendor/bin/behat features/save.for.later.feature
