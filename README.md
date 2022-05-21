<h1 align="center">App Transactions</h1>

<p>Implementation of Web Application of Example of Online Transactions using <strong>Laravel Jetstream (Inertia + Vue)</strong></p>

## 📝 Table of Contents

- [Prerequisites](#prerequisites)
- [Getting Started](#getting_started)
- [Manual of User](#manual_user)

## 🤓 Prerequisites <a name = "prerequisites"></a>

- Composer
- Npm

## 👍 Getting Started <a name = "getting_started"></a>

- Execute
    ```
    composer install
    ```

- Execute
    ```
    npn install
    ```

- Create database with name: `transacciones`

- Create file with name: `.env`

- Config data base in the file `.env`, for example: `DB_DATABASE=transacciones`

- Execute migrations with example data
    ```
    php artisan migrate:fresh --seed
    ```

- Execute 
    ```
    npm run prod
    ```

- Execute y open url 
    ```
    php artisan serve
    ```

## 📖 Manual of user

- Datos de usuario administrador: 
    - `identificacion` => `12345678`
    - `contraseña` => `1234`

- Restricciones: Solo el usuario administrador puede crear una nueva cuenta y asignarla a un usuario

- El Usuario regular los puede consultar ingresando con el usuario administrador
    - `contraseña` => `1234`

- `NOTA` Para realizar transacciones ingresar con un uisuario diferente al administrador.