#!/bin/sh
for i in `find ./tinymce/jscripts/tiny_mce -name langs`;
do
	merge=`echo $i | cut -f5- -d/ | sed -e 's/langs//'`;

	cp -r $i tinyMCE/$merge;
	if [ $? -eq 0 ]
	then
		echo "$i merged";
	fi
done;
