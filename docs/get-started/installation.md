# Installation

This guide explains how to install the Gatovel Framework and create a new application.

## Requirements

Before installing Gatovel, make sure your environment has:

- PHP 8.3 or higher
- Composer

Verify PHP:

```bash
php -v
```

Verify Composer:

```bash
composer --version
```

## Create a New Project

Create a Gatovel application with Composer:

```bash
composer create-project gatovel/framework my-site
```

Replace `my-site` with the name of your application.

For example:

```bash
composer create-project gatovel/framework blog
```

Composer creates the application and installs the dependencies required by the Gatovel Core.

## Gatovel Installer

After the project is created, the Gatovel interactive installer starts automatically.

It asks which optional Gatovel packages should be installed:

```text
Gatovel Framework Installer

? Do you want to install the Gatovel CLI? [yes/no]:
? Do you want to install Database support? [yes/no]:
? Do you want to install Gatovel Auth? [yes/no]:
```

The base framework does not require these packages.

Selecting a component causes the installer to add its compatible package through Composer.

The installer currently uses:

```text
gatovel/cli:^2.0
gatovel/database:^2.0
gatovel/auth:^1.0
```

## CLI and Database Dependency

Gatovel CLI 2.x depends on Gatovel Database 2.x because the CLI contains database-related commands.

Therefore, selecting CLI can cause Composer to install Database even when Database was not selected separately.

This is normal Composer dependency resolution.

## Installing Packages Later

Optional packages can also be installed after project creation.

CLI:

```bash
composer require gatovel/cli:^2.0
```

Database:

```bash
composer require gatovel/database:^2.0
```

Auth:

```bash
composer require gatovel/auth:^1.0
```

## Project Directory

After installation, enter the project directory:

```bash
cd my-site
```

Create the environment file:

```bash
cp .env.example .env
```

The application is now ready for configuration.

## Next Step

Continue with:

- [Configuration](configuration.md)
- [Architecture](../documentation/architecture.md)
