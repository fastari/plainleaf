# Common tasks. Run `make help` to list them.
.PHONY: help dev build lint palettes release

help: ## List the tasks
	@grep -E '^[a-z]+:.*## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*## "} {printf "  make %-9s %s\n", $$1, $$2}'

dev: ## Start the local WordPress site at http://127.0.0.1:9400
	npm run dev

build: ## Check and build the theme zip into dist/
	bash bin/build.sh

lint: ## PHPCS with WordPress Coding Standards (run composer install once first)
	vendor/bin/phpcs

palettes: ## Regenerate plainleaf/styles/colors from bin/make-palettes.py, then check contrast
	python3 bin/make-palettes.py && python3 bin/check-contrast.py

release: ## Release now without a merge: make release BUMP=patch|minor|major (default patch)
	gh workflow run release.yml -f bump=$(or $(BUMP),patch)
