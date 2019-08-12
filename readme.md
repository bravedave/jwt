# DVC Module Template

This is a template for creating a module for the DVC Framework

Installing this demo exposes the documentation for using the dvc framework. The documentation
is written using markdown and is part of the dvc framework. The documentation is also part 
brayworth's web site here : https://brayworth.com/docs/

## Install
To use DVC on a Windows 10 computer (Devel Environment)
1. Install PreRequisits
   * Install PHP : http://windows.php.net/download/
      * Install the non threadsafe binary
      * by default there is no php.ini (required)
        * copy php.ini-production to php.ini
        * edit and modify/add (uncomment)
          * extension=fileinfo
          * extension=sqlite3
          * extension=mbstring
          * extension=openssl
      * *note these instructions for PHP Version 7.2.7, the older syntax included .dll on windows*

   * Install Git : https://git-scm.com/
     * Install the *Git Bash Here* option
   * Install Composer : https://getcomposer.org/

1. Clone or download this repo
   * Start the *Git Bash* Shell
     * Composer seems to work best here, depending on how you installed Git
   * mkdir c:\data
   * cd c:\data
   * setup a new project
     * `composer create-project bravedave/dvc-module my-module @dev`
   * the project is now set up in c:\data\my-project
     * cd my-module

To run the demo
   * Review the run.cmd
     * The program is now accessible: http://localhost
     * Run this from the command prompt to see any errors - there may be a firewall
       conflict options to fix would be - use another port e.g. 8080
