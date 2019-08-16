install:
	composer install
run:
	php -S localhost:8000 -t public
test:
	phpunit
logs:
	tail -f storage/logs/lumen.log
