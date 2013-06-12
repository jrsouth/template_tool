#! /bin/bash
echo ".------------------------------.";
echo "| Clearing template tool cache |";
echo "'------------------------------'";
echo " - Removing old files...";
#rm -rf ./cache/*;

rm -rf ./cache/default/*.jpg
rm -rf ./cache/img/*.jpg
rm -rf ./cache/pdf/*.pdf
rm -rf ./cache/thumbnails/*.jpg


#echo " - Recreating subfolders...";
#cd cache;
#mkdir img pdf thumbnails default upload;
#echo " - Setting permissions...";
#chmod 0777 ./*;
echo "Done.";
