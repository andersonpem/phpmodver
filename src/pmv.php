<?php
/**
 * pmv - PHP Module Versioner
 * This is a small utility to help in handling version numbers in nwidart/laravel-modules.
 * Author: AndersonPEM
 * MIT Licensed
 */
system('clear');



class Module {
    /**
     * Class constructor.
     */
    public function __construct(){
        if ((file_exists(getcwd() . '/module.json')) && (file_exists(getcwd() . '/composer.json'))) {
            try {
                $this->modfile = file_get_contents(getcwd() . '/module.json');
                $this->compfile = file_get_contents(getcwd() . '/composer.json');
            } catch (Exception $ex) {
                $this->error("Error reading the module's files\nSomething went wrong. The files do exist but I can't read them.\nMaybe you don't have permissions for it. Check that :)");
            }
        }
        else {
            $this->error("Error: Module and/or composer files not found\nIt seems like your're not inside the folder of a nWidart Laravel Module.\nAre you in the main project's folder? I do that all the time :P\nIf so, get to your module's root folder before executing this utility.");
        }
        
        $this->compj = json_decode($this->compfile);
        if(empty($this->compj->type) || ($this->compj->type<>'laravel-module')){
            $this->warnAndDie(
                "Error: not a nWidart Laravel Module\nThe contents of this directory doesn't seem to be from a laravel-module.\nWell, at least not one that you can download from packagist.\nIf This is a laravel-module, include the line:\e[0;34;40m \n\n\"type\": \"laravel-module\",\n\e[1;33;40m\nin your module's composer.json file, so it can be identified as a module\nfor installers and in services like Packagist. Follow the rules :)");
        }
        $this->modj = json_decode($this->modfile);
        $this->verC = explode('.', $this->compj->version);
        $this->verM = explode('.', $this->modj->version);
        if ($this->checkMatch()) {
            $this->composername=$this->compj->name;
            $this->name=$this->modj->name;
            $this->major=$this->verC[0];
            $this->minor=$this->verC[1];
            $this->patch=$this->verC[2];
        }
        else {
            echo "\e[1;31;40m!!! There are version inconsistencies in your module's files !!!          \e[0m\n";
            echo "Composer file is at \e[1;31;40mv".$this->verC[0].'.'.$this->verC[1].'.'.$this->verC[2]. "\e[1;37;40m while the Module is at \e[1;31;40mv".$this->verM[0] . '.' . $this->verM[1] . '.' . $this->verM[2].".\n";
            echo "\e[1;37;40mYou can fix this by setting the value with this utility running:\n";
            echo "\e[0;34;40m pmv --setver 3.0.0 (example)\e[0m\n";

        }
    }
    //Module File
    protected $modfile;
    //Composer's module file
    protected $compfile;
    protected $compj;
    protected $modj;


    
    //Properties
    protected $name;
    protected $composername;
    protected $maj;
    protected $min;
    protected $patch;

    // Version as in the Module file
    protected $verM;
    // Version as in the Composer File
    protected $verC;
    
    /**
     * Checks if the data of both files match.
     */
    public function checkMatch(){
        $counter = 0;
        $verC= explode('.', $this->compj->version);
        $verM= explode('.', $this->modj->version);
        for ($i = 0; $i < 3; $i++) {
            if ($verC[$i] <> $verM[$i]) {
                $counter++;
            }
        }
        if ($counter>0) {
            return false;
        }
        else {
            return true;
        }
    }
    public function error($message){
        echo ("\e[1;31;40m".$message. "\e[0m\n");
        die();
    }
    protected function warnAndDie($message){
        echo ("\e[1;33;40m");
        echo ("------------------------------------------------------------------------\n");
        echo ($message) . "\n";
        echo ("------------------------------------------------------------------------\n");
        echo ("\e[0m\n");
        die();  
    }
    public function success($message){
        echo ("\n");
        echo ("\e[0;32;40m");
        echo ("-------------------------------------------------\n");
        echo ($message)."\n";
        echo ("-------------------------------------------------\n");
        echo ("\e[0m\n");
        die();
    }

    protected function inform($message){
        echo ("\e[0;32;40m");
        echo ($message) . "\n";
    }
    /**
     * Checks if the version string is valid. x.x.x
     *
     * @param [string] $ver
     * @return boolean
     */
    protected function verValid($ver){
        if (preg_match('/(^)([0-9][0-9]?)(\.)([0-9][0-9]?)(\.)([0-9][0-9]?)($)/', $ver)) {
            return true;
        }
        else{
            $this->error("Error in: setting up version. Error: SemVer incorrect.\nVersion numbering incorrect.\nVersion has to have 3 characters separated by dots (x.x.x)");
        }
    }
    protected function isSmaller($ver, $force){
        $arr = explode('.', $ver);
        if (($arr[0]<$this->maj) || ($arr[1]<$this->min) || ($arr[2]<$this->patch) && !($force)) {
            $this->error("Error: Can't update to an older version.\nThe version you entered is smaller than the current version: ".$this->compj->version."\nTo do it anyway, use the -f or the --force flag in the command's end.\n\nEx: pmv setver 4.5.6 -f");
        }

    }
    protected function isVerEqual($ver){
        $arr = explode('.', $ver);
        if (($arr[0] == $this->maj) || ($arr[1] == $this->min) || ($arr[2] == $this->patch)) {
            $this->error("Error: Version is equal\nThe version you specified is already the Module's version.");
        }
    }
    protected function persistVer($version, $force=false){
        //Runs the guards first
        $this->verValid($version);
        $this->isSmaller($version, $force);
        $this->isVerEqual($version);
        //Perform the operation
        $this->compj->version="$version";
        $this->modj->version="$version";
        $composer = fopen(getcwd() . '/composer.json', "w");
        fwrite($composer, json_encode($this->compj, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        fclose($composer);
        $module = fopen(getcwd() . '/module.json', "w");
        fwrite($module, json_encode($this->modj, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        fclose($module);
        $this->inform($this->modj->name." (". $this->compj->name.") is now at v". $this->compj->version . " in both Composer\nand nWidart's Module files.");
        $this->success("Changes have been persisted. You can pat yourself\nin the back and have some coffee. ^^");
    }
    
    public function version(){
        echo "$this->name version $this->maj.$this->min.$this->patch";
    }
    public function help(){
        echo "This is the help of the file";
    }
    protected function routes(){
        //The available functions
        return [
            'summary' => 'summary',
            'smr' => 'summary',
            's' => 'persistVer',
            'setver' => 'persistVer',
            'help' => 'help',
        ];
    }

    public function Router($argv){
        $routes = $this->Routes();
        $command = "";
        $parameter = "";
        $force=false;
        if (count($argv) > 1) {
            //Has at least an extra argument
            for ($i = 0; $i < count($routes)-1; $i++) {
                if (array_key_exists($argv[$i], $routes)) {
                    $command= $routes[$argv[$i]];
                    if (!empty($argv[2])) {
                        $parameter = $argv[2];
                    }
                    if (!empty($argv[3])) {
                        if(($argv[3] =='-f' )|| ($argv[3] == '--force')){
                            $force=true;
                        }
                    }
                    $this->$command($parameter, $force);
                    break;
                }
            }
        }
        else {
            echo "\e[1;34;40m============================================================================\n";
            echo "\e[0;37;40m  P.M.V. - PHP (nWidart) Module Version Management Utility by AndersonPEM\n";
            echo "\e[1;34;40m============================================================================\e[0m\n";
            echo "'Cause changing version files by hand is boring and I always forget about it ®\n";
            echo "Command structure: \n";
            echo "\e[0;34;40m    pmv command parameter\e[0m\n";
            echo "Or: \n";
            echo "\e[0;34;40m    pmv s parameter\e[0m\n\n";
            echo "Where S is the shorthand version of the full comand.\n";
            echo "\nFor the list of available commands in this script, type pmv help. \n";
        }

    }
}

    $module = new Module();
    $module->router($argv);
/**
 * Possible parameters:
 * no parameter (or -s): Returns a summary of the Module's version.
 * --setver: sets the entire module's version (major, minor and patch)
 * --setmajor: sets the major version number
 * --setminor: sets the minor version number
 * --setpatch: sets the patch version
 * --imaj: increments the major version
 * --imin: increments the minor version 
 * --ip: increments the patch version
 * 
 * All of them should return visual confirmation.
 * All the set and increment will parametrize the version in both files.
 */
?>