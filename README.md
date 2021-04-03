Pasos para instalar la aplicacion y poder utilizarla:
  1.- Ejecutar el comando composer install en la carpeta donde se baje la aplicacion.
  2.- Configurar en el .env la ruta de la base de datos y ejecutar los comandos para crear la base de datos y sus tablas:
            #php bin/console doctrine:database:create       //Comando para crear la base de datos
            #php bin/console make:migration
            #php bin/console doctrine:migrations:migrate
