# P.M.V. - PHP (nWidart) Module Version Management Utility

### Because coders (and the Sith) go for the easy and quick way

## What this is for

This script allows you to change your nWidart's Laravel Module version metadata in composer.json and module.json without having to touch the files. Just Type a command, the version, and bam! You're done.

I always keep forgetting to change the version of the module in the files and sometimes it is just boring to go after each of the files and make the changes and save the files and then doing a commit. So I made this shorthand command to handle the file parts. That way we get more time to make our customers happy :)

## How to use it

- In a terminal, get to your laravel-module root folder.
- Type: pmv _arg_ [flags] in a terminal to manipulate your Module's versioning metadata.
  
### Example

Suppose I have a laravel-module in which the current version is **0.2.0** and I want to change it to **0.3.0**. Here's how:

```bash
$ /main-laravel-project/
$ cd Modules/my-awesome-module
$ /main-laravel-project/Modules/my-awesome-module pmv 0.3.0

[expected output]

ModuleName (packagist/modulename) is now at v0.3.0 in both Composer
and nWidart's Module files.

-------------------------------------------------
Changes have been persisted. You can pat yourself
in the back and have some coffee. ^^
-------------------------------------------------
```

Type **pmv help** to know the available commands and shorthand commands for this script.

## How to Install

_Please notice you have to have PHP installed in your machine to use this script_

- Clone this repo somewhere in your machine and get into the cloned repo's folder.
- Make the installer executable (chmod +x install.sh)
- Run the install file (./install.sh)
- Be happy.

## License
This script is licensed under the MIT license.

## Contributing

Open an issue and make a pull request.

## Contributors

**AndersonPEM** Script Creator

(It's lonely in here. Lemme grab some coffee ^^)

### Support this project

Wanna buy me some coffee? Coffee is never enough XD
<p align="center">
<a href="https://www.buymeacoffee.com/andersonpem" target="_blank">
<img src="./bac.svg" width="300px" ></a></p>

Have some bitcoin lying around? I have a wallet :) tip the project there:

**1AuAt29skEdmtQBG4bJYEnHnbhVbpcZn** 