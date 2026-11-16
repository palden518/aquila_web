## James Cook University
## ICT1 Project - Aquila Cafe Website Redesign

### Project Instructions

1. Clone Repository
   
   ```
   git clone 'https://github.com/palden518/aquila_web.git'

   ```
2. Install Dependencies
   
   ```
   composer install

   ```
   NOTE: Please make sure that you have installed the package manager 'composer'

3. Install mysql
   
   ```
   mysql -u root -p

   ```


   ```
   CREATE DATABASE aquila

   ```

   NOTE: Run given .sql script in aquila database to populate database

4. Migrate and seed database
   
   ```
   php artisan migrate

   ```

   ```
   php artisan db:seed
   ```

5. Run app

    ```
    php artisan serve

    ```

