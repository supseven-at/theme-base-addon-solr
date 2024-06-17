.PHONY: docs
docs: ## Render docs locally
	@echo $current_dir
	@mkdir -p Documentation-GENERATED-temp; docker run --rm --pull always -v $(CURDIR):/project -it ghcr.io/typo3-documentation/render-guides:latest --config=Documentation
	@open Documentation-GENERATED-temp/Index.html