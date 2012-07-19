#!/bin/zsh

name=$1
versions=(${=$(git tag)})

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<updates>" > build/${name}_update.xml

for version in $versions; do
	for joomla_version in '1.6' '1.7'; do
		client_id="<client_id>1<\/client_id>"
		xml=$(cat update_template.xml | sed "s/###NAME###/$name/g" | sed "s/###VERSION###/$version/g" | sed "s/###CLIENT_ID###/$client_id/g" | sed "s/###JOOMLA_VERSION###/$joomla_version/g")
		echo $xml >> build/${name}_update.xml
	done

	# since 2.5 client_id is now client
	for joomla_version in '2.5'; do
		client_id="<client>1<\/client>"
		xml=$(cat update_template.xml | sed "s/###NAME###/$name/g" | sed "s/###VERSION###/$version/g" | sed "s/###CLIENT_ID###/$client_id/g" | sed "s/###JOOMLA_VERSION###/$joomla_version/g")
		echo $xml >> build/${name}_update.xml
	done
done

echo "</updates>" >> build/${name}_update.xml
