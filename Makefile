init:
	composer install

test:
	vendor/bin/phpunit --coverage-text
	vendor/bin/phpcs --version && echo && vendor/bin/phpcs -p --standard=PSR2 src tests
