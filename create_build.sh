#!/bin/zsh

# com_snk
name="com_snk"
currentversion=$(cat src/snk.xml| grep -i "<Version>" | cut -f 2 -d ">" | cut -f 1 -d "<")

if [ ! -e build ]; then
	mkdir build
fi

if [ ! -e build/$name-$currentversion-final.zip ]; then
	cd src
	zip -r $name-$currentversion-final.zip *
	mv $name-$currentversion-final.zip ../build/
	cd ..

	git tag -a $currentversion -m "version $currentversion"
fi

if [ ! -e build/${name}_update.xml ]; then
	versions=(${=$(git tag)})

	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<updates>" > build/${name}_update.xml

	for version in $versions; do
		for joomla_version in '1.6' '1.7' '2.5'; do
			for client_id in '' '<client_id>1<\/client_id>'; do
				xml=$(cat update_template.xml | sed "s/###NAME###/$name/g" | sed "s/###VERSION###/$version/g" | sed "s/###CLIENT_ID###/$client_id/g" | sed "s/###JOOMLA_VERSION###/$joomla_version/g")
				echo $xml >> build/${name}_update.xml
			done
		done
	done

	echo "</updates>" >> build/${name}_update.xml
fi

# copy to scoutnet 

cp build/${name}_update.xml ../scoutnet_download/
cp build/${name}-$currentversion-final.zip ../scoutnet_download/

cd ../scoutnet_download

ln -sf ${name}-$currentversion-final.zip ${name}-current-final.zip

cd ..

echo $currentversion > scoutnet_download/${name}_currentversion.txt

#svn add scoutnet_download/${name}-$currentversion-final.zip
#svn commit -m "new Version for ${name} $currentversion" scoutnet_download
