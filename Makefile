NAME = com_snk
XML = snk.xml
CURRENTVERSION = $(shell cat src/$(XML) | grep -i "<Version>" | cut -f 2 -d ">" | cut -f 1 -d "<")

default: tag build build/$(NAME)-$(CURRENTVERSION)-final.zip build/$(NAME)_update.xml

deploy: default ../scoutnet_download/$(NAME)-$(CURRENTVERSION)-final.zip

$(NAME)-%-final.zip: build/$(NAME)-%-final.zip ../scoutnet_download/$(NAME)-%-final.zip

tag: 
	@if [ ! -n $$(git tag -l $(CURRENTVERSION)) ]; then git tag -a $(CURRENTVERSION) -m "version $(CURRENTVERSION)"; fi

build/$(NAME)-%-final.zip:
	cd src; zip -r $(NAME)-$*-final.zip *
	mv src/$(NAME)-$*-final.zip build

build/$(NAME)_update.xml:
	bin/create_update_xml.sh $(NAME)

build:
	mkdir build

../scoutnet_download/$(NAME)-%-final.zip:
	cp build/$(NAME)_update.xml ../scoutnet_download/
	cp build/$(NAME)-$*-final.zip ../scoutnet_download/
	cd ../scoutnet_download; ln -sf $(NAME)-$*-final.zip $(NAME)-current-final.zip
	@echo $* > ../scoutnet_download/$(NAME)_version.txt
	svn add ../scoutnet_download/$(NAME)-$<-final.zip
	svn commit -m "new Version for $(NAME) $<" ../scoutnet_download

clean:
	rm -rf build
