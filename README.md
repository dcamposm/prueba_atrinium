Pasos para instalar la aplicacion y poder utilizarla:
  1.- Tener instalado symfony, composer y node.js.
  2.- Ejecutar los comandos en la carpeta donde se baje la aplicacion:
            #composer install
            #yarn install
            #yarn add @symfony/webpack-encore --dev
            #yarn encore dev
  3.- Configurar en el .env la ruta de la base de datos y ejecutar los comandos para crear la base de datos y sus tablas:
            #php bin/console doctrine:database:create       //Comando para crear la base de datos
            #php bin/console make:migration
            #php bin/console doctrine:migrations:migrate
