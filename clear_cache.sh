#! /bin/bash
echo ".------------------------------.";
echo "| Clearing template tool cache |";
echo "'------------------------------'";
echo " - Removing old files...";
rm -rf ./cache/*;
echo " - Recreating subfolders...";
cd cache;
mkdir img pdf thumbnails default upload;
echo " - Setting permissions...";
chmod 0777 ./*;
echo "Done.";
