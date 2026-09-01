# Configuration

After installing Gatovel, the next step is configuring your application.

Gatovel uses environment variables and configuration files to define application settings.

## Environment Variables

Create your environment file from the provided example:

```bash
cp .env.example .env
```

The `.env` file contains environment-specific values used by the application.

For example:

```env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost
```

> Do not commit your `.env` file to version control. Use `.env.example` to provide the required variables for other environments.

## Configuration Files

Application configuration is stored in the `config/` directory.

```text
config/
└── database.php
```

Configuration files contain settings used by specific parts of the application.

Environment variables can be used to provide values that should differ between environments.

## Application Environment

The application environment determines how Gatovel should behave.

A development environment may enable debugging:

```env
APP_ENV=development
APP_DEBUG=true
```

While a production environment should disable debugging:

```env
APP_ENV=production
APP_DEBUG=false
```

## Database Configuration

If Database support was selected during installation, database configuration can be defined through the environment file.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_database
DB_USERNAME=root
DB_PASSWORD=
```

The exact database options depend on the database component installed in the application.

## Next Step

Once your application has been configured, continue with:

* [First Application](first-application.md)