#!/bin/bash
for i in `find ./tinymce_language_pack -name langs`;
do
	cp -a $i/* `echo $i | sed s/tinymce_language_pack/tinymce/`/
done;