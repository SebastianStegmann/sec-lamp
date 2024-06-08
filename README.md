##############################
## Database
If you cannot connect to the database, try changing the value in _.php on line 12:
- ""
- "password"
- "root"


##############################
## Tailwindcss
- move to the tailwindcss folder from htdocs
$ cd tailwindcss
$ npx tailwindcss -i ./input.css -o ../app.css --watch


##############################
## Faker
https://github.com/fzaninotto/Faker

##############################
To use seeders, remove # from .htaccess in seeders folder
Go to ./seed_database.php in your browser
ADD # to htaccess





