# Installation

Gatovel Framework is distributed as a Composer project.

## Requirements

Before installing Gatovel, make sure your environment has:

- PHP 8.3 or newer
- Composer

## Create a new project

Create a new Gatovel application with Composer:

    composer create-project gatovel/framework my-site

Enter the project directory:

    cd my-site

During project creation, Gatovel automatically creates the `.env` file from `.env.example` when `.env` does not already exist.

The base installation contains only the Gatovel Core and its required dependencies.

## Optional packages

Gatovel components can be installed separately according to the needs of the application.

The currently supported optional packages are:

- `gatovel/cli:^2.0`
- `gatovel/database:^2.0`
- `gatovel/auth:^1.0`

To run the interactive package installer:

    php src/Installer.php

The installer asks whether you want to install:

- Gatovel CLI
- Database support
- Gatovel Auth

Answer `yes` or `no` for each component.

## Install packages manually

Optional packages can also be installed directly with Composer.

### CLI

    composer require gatovel/cli:^2.0

### Database

    composer require gatovel/database:^2.0

### Auth

    composer require gatovel/auth:^1.0

## CLI and Database

Gatovel CLI 2.x uses the Gatovel Database package for migration and seeder commands.

Because of this dependency, installing the CLI also installs a compatible `gatovel/database` 2.x version automatically.

The Database package can still be installed independently when the CLI is not required.

## Running the application

Configure your web server so that the application entry point is:

    public/index.php

During local development, make sure the environment variables in `.env` match the application environment.

## Minimal installation

If no optional packages are installed, Gatovel remains a functional Core framework with routing, controllers, middleware support, views, responses, configuration, error handling, logging, and mail infrastructure.

Optional components can be added later through Composer.
