# Gatovel Framework

The **Gatovel Framework** is a modular and extensible PHP framework designed for building modern web applications.

Gatovel provides a lightweight foundation for PHP applications while allowing developers to choose which additional components they want to install according to the needs of each project.

---

## 🚀 Get Started

Start building your first Gatovel application.

<p align="center">
  <a href="docs/get-started/installation.md">
    <img src="https://img.shields.io/badge/Installation-4CAF50?style=for-the-badge" alt="Installation">
  </a>
  <a href="docs/get-started/configuration.md">
    <img src="https://img.shields.io/badge/Configuration-2196F3?style=for-the-badge" alt="Configuration">
  </a>
  <a href="docs/get-started/first-application.md">
    <img src="https://img.shields.io/badge/First_Application-9C27B0?style=for-the-badge" alt="First Application">
  </a>
</p>

---

## 📚 Documentation

Explore the Gatovel Framework architecture and components.

<p align="center">
  <a href="docs/documentation/architecture.md">
    <img src="https://img.shields.io/badge/Architecture-607D8B?style=for-the-badge" alt="Architecture">
  </a>
  <a href="docs/documentation/core.md">
    <img src="https://img.shields.io/badge/Core-455A64?style=for-the-badge" alt="Core">
  </a>
  <a href="docs/documentation/routing.md">
    <img src="https://img.shields.io/badge/Routing-795548?style=for-the-badge" alt="Routing">
  </a>
  <a href="docs/documentation/middleware.md">
    <img src="https://img.shields.io/badge/Middleware-FF9800?style=for-the-badge" alt="Middleware">
  </a>
</p>

<p align="center">
  <a href="docs/documentation/cli.md">
    <img src="https://img.shields.io/badge/CLI-3F51B5?style=for-the-badge" alt="CLI">
  </a>
  <a href="docs/documentation/database.md">
    <img src="https://img.shields.io/badge/Database-009688?style=for-the-badge" alt="Database">
  </a>
  <a href="docs/documentation/configuration.md">
    <img src="https://img.shields.io/badge/Configuration-03A9F4?style=for-the-badge" alt="Configuration">
  </a>
</p>

---

## Installation

Create a new Gatovel project using Composer:

```bash
composer create-project gatovel/framework my-site
```

During the installation process, Gatovel will ask which optional components you want to use:

```text
Gatovel Framework Installer

? Do you want to install additional tools? [yes/no]:
? Do you want to install Database support? [yes/no]:
? Do you want to install Miau? [yes/no]:
```

This allows each project to be configured according to its requirements.

For example, a simple landing page may not require database support, while a larger application can install additional components when needed.

---

## Modular Architecture

Gatovel is designed around a modular architecture.

```text
Gatovel Framework
        │
        ├── Core
        │
        ├── Installer
        │
        ├── Optional Components
        │       │
        │       ├── Development Tools
        │       ├── Database
        │       ├── Frontend
        │       └── Authentication
        │
        └── Future Components
```

The goal is to keep the base framework lightweight while allowing applications to add functionality as required.

Each optional component can have its own package, repository, versioning, and development cycle.

---

## Installer

The Gatovel Installer is responsible for configuring a new project after its creation.

It allows developers to select the components they want to use instead of installing every available feature by default.

This approach helps keep projects smaller and avoids unnecessary dependencies.

As the framework evolves, new installation options can be added without changing the fundamental structure of the framework.

---

## Project Structure

A newly created Gatovel application follows a structure similar to:

```text
my-site/
│
├── config/
│
├── public/
│   ├── index.php
│   └── .htaccess
│
├── src/
│   ├── controllers/
│   ├── middlewares/
│   ├── models/
│   ├── routes/
│   ├── views/
│   └── nucleo/
│
├── bootstrap.php
├── composer.json
├── composer.lock
└── .env.example
```

The `src/nucleo` directory contains the framework's internal foundation, while the other application directories contain project-specific code.

---

## Requirements

* PHP 8.3 or higher
* Composer

---

## Development Status

Gatovel Framework is currently under active development.

The project is being designed with **modularity, extensibility, and simplicity** as its core principles.

Planned components include:

* Database integration
* Authentication
* Frontend integration
* Additional development tools
* More generators and utilities

---

## License

MIT License

Copyright (c) 2026 Gatove
l
