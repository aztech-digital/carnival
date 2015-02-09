test: phpunit phpcs bugfree phpmd
test-analysis: phpcs bugfree phpmd
test-upload: scrutinizer

.PHONY: test test-analysis test-upload pretest phpunit phpcs phpmd bugfree ocular scrutinizer clean clean-env clean-deps

pretest:
	composer install --dev
	
phpunit: pretest
	[ ! -d tests/output ] || mkdir -p tests/output
	vendor/bin/phpunit --coverage-text --coverage-clover=tests/output/coverage.clover

ifndef STRICT
STRICT = 0
endif

ifeq "$(STRICT)" "1"
phpcs: pretest
	vendor/bin/phpcs --standard=PSR2 src
else
phpcs: pretest
	vendor/bin/phpcs --standard=PSR2 -n src
endif

bugfree: pretest
	[ ! -f bugfree.json ] || vendor/bin/bugfree generateConfig
	vendor/bin/bugfree lint src -c bugfree.json

phpcbf: pretest
	vendor/bin/phpcbf --standard=PSR2 src

phpmd: pretest
	vendor/bin/phpmd src/ text design,naming,cleancode,codesize,controversial,unusedcode

ocular:
	[ ! -f ocular.phar ] && wget https://scrutinizer-ci.com/ocular.phar

ifdef OCULAR_TOKEN
scrutinizer: ocular
	@php ocular.phar code-coverage:upload --format=php-clover tests/output/coverage.clover --access-token=$(OCULAR_TOKEN);
else
scrutinizer: ocular
	php ocular.phar code-coverage:upload --format=php-clover tests/output/coverage.clover;
endif

clean: clean-env clean-deps

clean-env:
	rm -rf coverage.clover
	rm -rf ocular.phar
	rm -rf tests/output/
	
clean-deps:
	rm -rf vendor/
