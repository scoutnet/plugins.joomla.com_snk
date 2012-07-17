#!/bin/zsh

# com_snk
name="com_snk"
version=$(cat src/$name.xml| grep -i "<Version>" | cut -f 2 -d ">" | cut -f 1 -d "<")

if [ ! -e build ]; then
	mkdir build
fi


if [ ! -e build/$name-$version-final.zip ]; then
	cd src
	zip -r $name-$version-final.zip *
	mv $name-$version-final.zip ../build/
	cd ..

	# TODO: create TAG 
	# git add build/$name-$version-final.zip

xml=$(cat build/${name}_update.xml | grep -v "</updates>")

echo $xml > build/${name}_update.xml

for joomla_version in '1.6' '1.7' '2.5'; do
for client_id in '' '<client_id>1</client_id>'; do

xml="	<update>
		<name>ScoutNet Kalender</name>
		<description>official ScoutNet Kalender</description>
		<element>$name</element>
		<type>component</type>
		<version>$version</version>
		$client_id

		<infourl title=\"ScoutNet URL\">http://www.scoutnet.de</infourl>
		<downloads>
			<downloadurl type=\"full\" format=\"zip\">https://www.scoutnet.de/technik/kalender/plugins/joomla/$name-$version-final.zip</downloadurl>
		</downloads>
		<tags>
			<tag>ScoutNet</tag>
			<tag>Muetze</tag>
		</tags>

		<maintainer>ScoutNet (Mütze)</maintainer>
		<maintainerurl>http://www.scoutnet.de</maintainerurl>
		<section>ScoutNet Kalender</section>

		<targetplatform name=\"joomla\" version=\"$joomla_version\" />
	</update>"

echo $xml >> build/${name}_update.xml
done
done

echo "</updates>" >> build/${name}_update.xml

# TODO: commit changes to git
# svn commit -m "new Version for $name $version"

cp build/${name}_update.xml ../scoutnet_download/
cp build/${name}-$version-final.zip ../scoutnet_download/

cd ../scoutnet_download

ln -sf ${name}-$version-final.zip ${name}-current-final.zip

cd ..

echo $version > scoutnet_download/${name}_version.txt

svn add scoutnet_download/${name}-$version-final.zip
svn commit -m "new Version for ${name} $version" scoutnet_download
fi

