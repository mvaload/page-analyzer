install:
	composer install
run:
	php -S localhost:8000 -t public
lint:
	composer run-script phpcs -- --standard=PSR12 --ignore=*/vendor/*,*/tests/*,*/bootstrap/*,*/storage/*,*/resources/*,*/config/*,*/public/index.php,*/database/*,*.css,*.js ./
test:
	composer run-script phpunit tests
logs:
	tail -f storage/logs/lumen.log
