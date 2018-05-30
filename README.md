1) Save repository in XAMPP/htdocs
2) Create a database called tasks using http://localhost/phpmyadmin/
3) In Basic-Crud-Symfony-/.env file replace <insert username> and <insert password> with desired username and password for your database. Can be found on line 23
4) To create the tables in the database, use a command line, and make sure you are in the Basic-Crud-Symfony directory. Execute the 'php bin/console make:migration' command followed up by the 'php bin/console doctrine:migrations:migrate' command.
5) Using a command line, while in the the Basic-Crud-Symfony- directory and execute the command: 'php bin/console server:start'

