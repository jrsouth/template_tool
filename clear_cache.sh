#! /bin/bash
echo ".------------------------------.";
echo "| Clearing template tool cache |";
echo "'------------------------------'";
echo " - Removing old files...";
#rm -rf ./cache/*;

rm -rf ./cache/default/*.jpg ./cache/default/*.png
rm -rf ./cache/img/*.jpg ./cache/img/*.png
rm -rf ./cache/pdf/*.pdf
rm -rf ./cache/thumbnails/*.jpg ./cache/thumbnails/*.png


#echo " - Recreating subfolders...";
#cd cache;
#mkdir img pdf thumbnails default upload;
#echo " - Setting permissions...";
#chmod 0777 ./*;
echo "Done.";
