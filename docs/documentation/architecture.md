# Architecture

Gatovel is designed as a modular and extensible PHP framework.

Its architecture is based on a lightweight Core combined with optional Composer packages that can be installed according to the needs of each application.

## Architecture Overview

A Gatovel application consists of the Gatovel Core and, optionally, additional Gatovel packages.

```text
                     GATOVEL APPLICATION
                             │
             ┌───────────────┴───────────────┐
             │                               │
             ▼                               ▼
      ┌──────────────┐                ┌───────────────┐
      │ GATOVEL CORE │                │   OPTIONAL    │
      │              │                │   PACKAGES    │
      └──────┬───────┘                └───────┬───────┘
             │                                │
      ┌──────┴──────┐              ┌──────────┼──────────┐
      │             │              │          │          │
      │ Request     │              ▼          ▼          ▼
      │ Router      │             CLI      Database     Auth
      │ Middleware  │
      │ View        │
      │ Response    │
      │ Errors      │
      │ Logging     │
      │ Mail        │
      │ Config      │
      └─────────────┘
```

The Core provides the fundamental infrastructure required to run a Gatovel application.

Optional functionality is distributed through independent Composer packages.

## Core

The Gatovel Core is the foundation of the framework.

It is responsible for the HTTP lifecycle and the fundamental services required by an application.

The Core includes:

- Request handling
- Routing
- Middleware execution
- Controller resolution
- View rendering
- Response handling
- Configuration
- Error handling
- Logging
- Mail
- Application lifecycle

The Core does not require the Gatovel CLI, Database, or Auth packages in order to run.

This keeps the base framework lightweight and prevents applications from installing functionality they do not need.

## Optional Packages

Additional functionality is provided through independent Composer packages.

The currently supported optional packages are:

```text
gatovel/cli
gatovel/database
gatovel/auth
```

They can be selected during project installation or installed later using Composer.

### CLI

The CLI package provides command-line tools for Gatovel applications.

The current compatible version is:

```text
gatovel/cli ^2.0
```

The CLI 2.x package uses the Gatovel Database package for its database-related commands. Therefore, installing the CLI also installs a compatible Database package through Composer.

### Database

The Database package provides the persistence layer independently from the Core.

The current compatible version is:

```text
gatovel/database ^2.0
```

It provides database-related functionality such as connections, queries, transactions, schema operations, migrations and seeders.

### Auth

The Auth package provides authentication and authorization-related components independently from the Core.

The current compatible version is:

```text
gatovel/auth ^1.0
```

The package contains authentication-related functionality without making Auth a dependency of the Gatovel Core.

## Installer

When a project is created with:

```bash
composer create-project gatovel/framework my-site
```

Gatovel runs its interactive installer.

The installer asks which optional packages should be added:

```text
Gatovel Framework Installer

? Do you want to install the Gatovel CLI? [yes/no]:
? Do you want to install Database support? [yes/no]:
? Do you want to install Gatovel Auth? [yes/no]:
```

Only the selected packages are explicitly added to the application.

Composer can also install transitive dependencies required by a selected package. For example, Gatovel CLI 2.x requires Gatovel Database 2.x.

## Composer Packages

The package architecture is:

```text
gatovel/framework
│
├── Core
│
├── gatovel/cli       optional
├── gatovel/database  optional
└── gatovel/auth      optional
```

The framework package contains the Core and application skeleton.

Optional components are versioned and distributed separately.

## Application Lifecycle

The HTTP lifecycle begins at the public entry point:

```text
HTTP Request
     │
     ▼
public/index.php
     │
     ▼
Bootstrap
     │
     ▼
Request
     │
     ▼
Router
     │
     ▼
Controller Resolution
     │
     ▼
Method Resolution
     │
     ▼
Route Parameters
     │
     ▼
Middleware Pipeline
     │
     ▼
Controller
     │
     ▼
Response
     │
     ▼
HTTP Response
```

### Entry Point

HTTP requests enter the application through:

```text
public/index.php
```

The entry point loads the bootstrap and starts request processing.

### Bootstrap

The bootstrap prepares the application environment.

It loads Composer's autoloader, environment variables, application configuration, providers and routes.

### Request

The `Request` component provides an abstraction over the incoming HTTP request.

It provides access to information such as:

- HTTP method
- URI
- Query parameters
- POST data
- Request input
- HTTP headers

### Routing

The Router determines which controller and method should handle an incoming request.

Routes can also contain parameters:

```text
/users/{id}
```

The Router distinguishes between valid routes, routes that do not exist and routes called with unsupported HTTP methods.

### Controller Resolution

After a route is matched, Gatovel resolves the controller and method responsible for processing the request.

The framework separates this process into lifecycle stages with defined responsibilities.

### Middleware

Middleware can process a request before it reaches the controller.

```text
Request
   │
   ▼
Middleware
   │
   ▼
Middleware
   │
   ▼
Controller
   │
   ▼
Response
```

Middleware can inspect requests, stop processing, pass execution to the next middleware and process the resulting response.

### Controller

Controllers contain application-specific request handling logic.

The Core provides the infrastructure for invoking controllers but does not define application business logic.

### View

The View component renders application views and keeps presentation separate from routing and controller resolution.

### Response

The `Response` component represents the result returned to the client.

Responses can include:

- HTML
- JSON
- Redirects
- HTTP status codes
- HTTP headers

## Separation of Responsibilities

Gatovel separates framework infrastructure from application code.

```text
Gatovel Core
│
├── HTTP lifecycle
├── Routing
├── Middleware
├── Request / Response
├── Views
├── Configuration
├── Errors
├── Logging
└── Mail

Application
│
├── Controllers
├── Services
├── Routes
├── Views
└── Business logic
```

Database models and persistence-related application code can be added when the Database package is installed.

Authentication-related application code can be added when the Auth package is installed.

## Modularity

Modularity is a fundamental principle of Gatovel.

The Core does not depend on optional Gatovel packages.

```text
              ┌──────────────┐
              │ Gatovel Core │
              └──────┬───────┘
                     │
          ┌──────────┼──────────┐
          │          │          │
          ▼          ▼          ▼
         CLI      Database     Auth
       optional    optional    optional
```

This architecture allows packages to be:

- developed independently;
- versioned independently;
- installed only when required;
- updated independently;
- extended without unnecessarily increasing the Core.

## Design Principles

### Lightweight Core

The Core contains the fundamental functionality required to run a Gatovel application.

### Modularity

Additional functionality should be provided through independent packages whenever possible.

### Separation of Responsibilities

Each component should have a clear responsibility.

### Extensibility

Applications should be able to extend framework behavior without modifying the Core.

### Independent Evolution

Optional packages should be able to evolve independently from the Core.

### Composer Integration

Composer is the package management mechanism used by Gatovel and its optional packages.
