# Lejardindescurieux

C'est un projet fin de formation au sein de société Alain DURET.

Page d'accueil
![image](https://user-images.githubusercontent.com/101636695/183625996-03e4af22-0b24-41d9-ac65-20b9cb9c0ca3.png)

Page administrateur
![image](https://user-images.githubusercontent.com/101636695/183626251-16478e1b-6845-49e2-9666-11d09d3b9d1a.png)

Cloner le projet et executer les lignes de commandes suivantes :

    Bien se placer dans le dossier du projet avant d'utiliser ces commandes !

composer install
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migration:migrate
symfony console doctrine:fixtures:load

npm install
npm run build

Mise à jour

composer install
symfony console doctrine:schema:update --force
npm install
npm run build
