test:
	composer run-script phpunit tests
install:
	composer install
run:
	php -S localhost:8000 -t public
logs:
	tail -f storage/logs/lumen.log
lint:
	composer run-script phpcs -- --standard=PSR12 app routes tests
