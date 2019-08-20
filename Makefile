install:
	composer install
run:
	php -S localhost:8000 -t public
lint:
	composer run-script phpcs -- --standard=PSR12 app public tests
test:
	composer run-script phpunit tests
logs:
	tail -f storage/logs/lumen.log
