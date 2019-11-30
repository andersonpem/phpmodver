<?php
/**
 * pmv - PHP Module Versioner
 * This is a small utility to help in handling version numbers in nwidart/laravel-modules.
 * Author: AndersonPEM
 * MIT Licensed
 */
system('clear');
echo "\e[1;34;40m=================================================================\n";
echo "\e[0;37;40m       Module Version Management Utility by AndersonPEM\n";
echo "\e[1;34;40m=================================================================\e[0m\n";
//  var_dump($argv);

class Module {
    /**
     * Class constructor.
     */
    public function __construct(){
        $this->modfile = file_get_contents(getcwd().'/module.json');
        $this->compfile = file_get_contents(getcwd() . '/composer.json');
        $this->compj = json_decode($this->compfile);
        $this->modj = json_decode($this->modfile);
        $this->verC = explode('.', $this->compj->version);
        $this->verM = explode('.', $this->modj->version);
        if ($this->checkMatch()) {
            echo "The files are consistent\n";
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

    public function version(){
        echo "$this->name version $this->maj.$this->min.$this->patch";
    }
    public function diag(){
       // var_dump();
    }
    }

    $module = new Module();
    echo $module->checkMatch();
/**
 * Possible parameters:
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