#!/bin/zsh

# com_snk
version=$(cat component/snk.xml| grep -i "<Version>" | cut -f 2 -d ">" | cut -f 1 -d "<")

if [ ! -e builds/com_snk-$version-final.zip ]; then
	svn export component export
	cd export
	zip -r com_snk-$version-final.zip *
	mv com_snk-$version-final.zip ../build/
	cd ..
	rm -rf export

	svn add build/com_snk-$version-final.zip

	
xml=$(cat build/com_snk_update.xml | grep -v "</updates>")

echo $xml > build/com_snk_update.xml

for joomla_version in '1.6' '1.7' '2.5'; do
for client_id in '' '<client_id>1</client_id>'; do

xml="	<update>
		<name>ScoutNet Kalender</name>
		<description>official ScoutNet Kalender</description>
		<element>com_snk</element>
		<type>component</type>
		<version>$version</version>
		$client_id

		<infourl title=\"ScoutNet URL\">http://www.scoutnet.de</infourl>
		<downloads>
			<downloadurl type=\"full\" format=\"zip\">https://www.scoutnet.de/technik/kalender/plugins/joomla/com_snk-$version-final.zip</downloadurl>
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

echo $xml >> build/com_snk_update.xml
done
done

echo "</updates>" >> build/com_snk_update.xml

svn commit -m "new Version for com_snk $version"

cp build/com_snk_update.xml scoutnet_download/
cp build/com_snk-$version-final.zip scoutnet_download/

cd scoutnet_download

ln -sf com_snk-$version-final.zip com_snk-current-final.zip

cd ..

echo $version > scoutnet_download/com_snk_version.txt

svn add scoutnet_download/com_snk-$version-final.zip
svn commit -m "new Version for com_snk $version" scoutnet_download

fi
