# Installation

This guide explains how to install the Gatovel Framework and create a new application.

## Requirements

Before installing Gatovel, make sure your environment has:

* PHP 8.3 or higher
* Composer

You can verify your PHP version with:

```bash
php -v
```

And Composer with:

```bash
composer --version
```

## Create a New Project

Create a new Gatovel application using Composer:

```bash
composer create-project gatovel/framework my-site
```

Replace `my-site` with the name of your application.

For example:

```bash
composer create-project gatovel/framework blog
```

Composer will create the application inside the `blog` directory.

## Gatovel Installer

After the project is created, Gatovel provides an interactive installer to configure the application.

The installer allows you to choose which optional components should be included in the project.

For example:

```text
Gatovel Framework Installer

? Do you want to install additional tools? [yes/no]:
? Do you want to install Database support? [yes/no]:
? Do you want to install Miau? [yes/no]:
```

This approach keeps the base framework lightweight and allows each application to install only the functionality it requires.

## Optional Components

Optional components can be selected during the installation process.

For example, an application that does not require database functionality can skip Database support.

An application that requires database features can enable the Database component during installation.

This allows Gatovel applications to have different sets of components depending on their requirements.

## Project Directory

After installation, enter the newly created project:

```bash
cd my-site
```

The Gatovel application is now ready for configuration.

Continue to:

* [Configuration](configuration.md)
* [First Application](first-application.md)
