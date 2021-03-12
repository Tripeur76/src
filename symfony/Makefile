fixtures-test:
    php bin/console doctrine:fixtures:load -n --env=test
fixtures-dev:
    php bin/console doctrine:fixtures:load -n --env=dev
database-test:
    php bin/console doctrine:database:drop --if-exists --force --env=test
    php bin/console doctrine:database:create --env=test
    php bin/console doctrine:schema:update --force --env=test

database-dev:
    php bin/console doctrine:database:drop --if-exists --force --env=dev
    php bin/console doctrine:database:create --env=dev
    php bin/console doctrine:schema:update --force --env=dev
        
prepare-test:
	make database-test
	make fixtures-test

prepare-dev:
	make database-dev
	make fixtures-dev
