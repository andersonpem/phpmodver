#!/bin/bash
echo -e "\e[33mThis Script requires root privileges"
sudo clear;
echo -e "\e[33mInstalling..."
echo "======================================="
echo "   Step 1 - Removing old versions..."
sudo rm /usr/local/bin/pmv
echo "   Step 2 - Adding the new version..."
sudo cp ./src/pmv.php /usr/local/bin/pmv
echo "   Step 3 - Applying execution permissions..."
sudo chown $USER:$USER /usr/local/bin/pmv
sudo chmod +x /usr/local/bin/pmv
sudo chmod u+x /usr/local/bin/pmv
echo "   Aaaand we're done."
echo "======================================="
echo "Use the command pmv (php module version)   
to interact with nwidart modules' versions."
echo "Type pmv help to see the available commands.

Keep in mind you have to have PHP installed
in your machine for this script to work, ok?"
echo -e "
Press enter to continue"
read -p ".."
exit