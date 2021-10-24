install:
	composer install
lint:
	composer exec --verbose phpcs
	composer exec --verbose phpstan
fix:
	composer exec --verbose phpcbf
