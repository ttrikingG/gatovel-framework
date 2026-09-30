# Configuration

After installing Gatovel, configure the application environment.

Gatovel uses environment variables and PHP configuration files.

## Environment File

Create `.env` from the provided example:

```bash
cp .env.example .env
```

The base environment file contains settings used by the Core:

```env
APP_NAME=Gatovel
APP_ENV=local
APP_DEBUG=true

LOG_ENABLED=true
LOG_PATH=

MAIL_TRANSPORT=log
MAIL_LOG_PATH=
```

The `.env` file contains environment-specific values and should not be committed to version control.

The `.env.example` file documents the environment variables expected by the base application.

## Core Configuration

The base framework contains:

```text
config/
├── app.php
├── logging.php
└── mail.php
```

### Application

`config/app.php` contains application-level settings such as:

- application name;
- environment;
- debug mode;
- application providers.

### Logging

`config/logging.php` configures the Core logging system.

Environment variables:

```env
LOG_ENABLED=true
LOG_PATH=
```

When no custom path is provided, Gatovel uses its default log location.

### Mail

`config/mail.php` configures the Core mail system.

The default transport is the log transport:

```env
MAIL_TRANSPORT=log
MAIL_LOG_PATH=
```

## Optional Package Configuration

Configuration for optional packages is not part of the base Core configuration.

Installing an optional package adds functionality to the project, but application-specific configuration must be defined according to the package being used.

### Database

The Gatovel Database package is optional.

When an application uses Database, database environment variables can be defined according to its connection requirements, for example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_database
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

These variables are not included in the base `.env.example` because Database is not a Core dependency.

### Auth

The Gatovel Auth package is optional.

Authentication, OAuth and other Auth-specific configuration should only be added by applications that use those features.

Auth-specific environment variables are therefore not included in the base `.env.example`.

## Application Environment

For local development:

```env
APP_ENV=local
APP_DEBUG=true
```

For production:

```env
APP_ENV=production
APP_DEBUG=false
```

Debug mode should normally be disabled in production.

## Next Step

For more information about the framework structure, continue with:

- [Architecture](../documentation/architecture.md)
