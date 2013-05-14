NAME = com_snk
XML = snk.xml
CURRENTVERSION = $(shell cat src/$(XML) | grep -i "<Version>" | cut -f 2 -d ">" | cut -f 1 -d "<")
TAGS = $(shell git show-ref --tags | sed "s/ /_/g")

CURRENTVERSION_MAJOR = $(shell echo '$(CURRENTVERSION)' | cut -d '.' -f 1)
CURRENTVERSION_MINOR = $(shell echo '$(CURRENTVERSION)' | cut -d '.' -f 2)
CURRENTVERSION_PATCH = $(shell echo '$(CURRENTVERSION)' | cut -d '.' -f 3)

NEXTVERSION_MAJOR = $(shell echo "$$(($(CURRENTVERSION_MAJOR)+1)).0.0")
NEXTVERSION_MINOR = $(shell echo "$(CURRENTVERSION_MAJOR).$$(($(CURRENTVERSION_MINOR) + 1)).0")
NEXTVERSION_PATCH = $(shell echo "$(CURRENTVERSION_MAJOR).$(CURRENTVERSION_MINOR).$$(($(CURRENTVERSION_PATCH) + 1))")

default: build build/$(NAME)-$(CURRENTVERSION)-final.zip build/$(NAME)_update.xml


push:
	git push --tags

step_major:
	@echo 'new Version [$(NEXTVERSION_MAJOR)]'
	cat src/$(XML) | sed "s/<version>[0-9]*[.][0-9]*[.][0-9]*<\/version>/<version>$(NEXTVERSION_MAJOR)<\/version>/g" > src/_$(XML)
	mv src/_$(XML) src/$(XML)
	# Tag current Version
	make tag$(NEXTVERSION_MAJOR)
	# Commit version xml to git
	git add src/$(XML)
	git commit -m "new Version [$(NEXTVERSION_MAJOR)]"
	# build new version
	make default

step_minor:
	@echo 'new Version [$(NEXTVERSION_MINOR)]'
	cat src/$(XML) | sed "s/<version>[0-9]*[.][0-9]*[.][0-9]*<\/version>/<version>$(NEXTVERSION_MINOR)<\/version>/g" > src/_$(XML)
	mv src/_$(XML) src/$(XML)
	# Tag current Version
	make tag$(NEXTVERSION_MINOR)
	# Commit version xml to git
	git add src/$(XML)
	git commit -m "new Version [$(NEXTVERSION_MINOR)]"
	# build new version
	make default

step_patch:
	@echo 'new Version [$(NEXTVERSION_PATCH)]'
	cat src/$(XML) | sed "s/<version>[0-9]*[.][0-9]*[.][0-9]*<\/version>/<version>$(NEXTVERSION_PATCH)<\/version>/g" > src/_$(XML)
	mv src/_$(XML) src/$(XML)
	# Commit version xml to git
	git add src/$(XML)
	git commit -m "new Version [$(NEXTVERSION_PATCH)]"
	# Tag current Version
	make tag$(NEXTVERSION_PATCH)
	# build new version
	make default

tag: tag$(CURRENTVERSION)

tag%:
	#Tag only if not existent
	@if [ -z "$$(git tag -l $*)" ]; then git tag -a $* -m "version $*"; echo 'create tag [$*]'; else echo 'already Taged'; fi;

build/$(NAME)-%-final.zip:
	cd src; zip -r $(NAME)-$*-final.zip *
	mv src/$(NAME)-$*-final.zip build

build/$(NAME)_update.xml:
	bin/create_update_xml.sh $(NAME)

build:
	mkdir build

$(NAME)-%-final.zip: build/$(NAME)-%-final.zip ../scoutnet_download/$(NAME)-%-final.zip

# REMOVE and copy to external script
#../scoutnet_download/$(NAME)-%-final.zip:
#	cp build/$(NAME)_update.xml ../scoutnet_download/
#	cp build/$(NAME)-$*-final.zip ../scoutnet_download/
#	cd ../scoutnet_download; ln -sf $(NAME)-$*-final.zip $(NAME)-current-final.zip
#	@echo $* > ../scoutnet_download/$(NAME)_version.txt
#	svn add ../scoutnet_download/$(NAME)-$<-final.zip
#	svn commit -m "new Version for $(NAME) $<" ../scoutnet_download

clean:
	rm -rf build
