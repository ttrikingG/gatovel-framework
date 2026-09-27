# Architecture

Gatovel is designed as a modular and extensible PHP framework.

Its architecture is based on a lightweight core combined with optional components that can be installed independently according to the needs of each application.

## Architecture Overview

A Gatovel application is composed of the Gatovel Core and, optionally, additional components.

                         GATOVEL APPLICATION
                                  ┃
                 ┏━━━━━━━━━━━━━━━━┻━━━━━━━━━━━━━━━━┓
                 ┃                                 ┃
                 ▼                                 ▼
        ┏━━━━━━━━━━━━━━━━┓              ┏━━━━━━━━━━━━━━━━━━━━┓
        ┃  GATOVEL CORE  ┃              ┃ OPTIONAL COMPONENTS┃
        ┣━━━━━━━━━━━━━━━━┫              ┗━━━━━━━━━━┳━━━━━━━━━┛
        ┃                ┃                         ┃
        ┃  Request       ┃              ┏━━━━━━━━━╋━━━━━━━━━┓
        ┃  Router        ┃              ┃         ┃         ┃
        ┃  Middleware    ┃              ▼         ▼         ▼
        ┃  View          ┃          ┏━━━━━━━┓ ┏━━━━━━━━━┓ ┏━━━━━━━┓
        ┃  Response      ┃          ┃  CLI  ┃ ┃Database ┃ ┃ Miau  ┃
        ┃  Lifecycle     ┃          ┗━━━┳━━━┛ ┗━━━━┳━━━━┛ ┗━━━┳━━━┛
        ┗━━━━━━━━━━━━━━━━┛              ┃           ┃          ┃
                                        ▼           ▼          ▼
                                    Composer    Composer    Composer
                                     Package     Package     Package

The Core provides the fundamental functionality required to run a Gatovel application.

Additional functionality is provided through independent Composer packages.

## Core

The Gatovel Core is the foundation of the framework.

It is responsible for the fundamental application lifecycle and HTTP request processing.

The Core includes functionality such as:

* Request handling
* Routing
* Middleware execution
* Controller resolution
* View rendering
* Response handling
* Error handling
* Application lifecycle

The Core is intentionally kept lightweight so applications do not have to install functionality they do not need.

## Optional Components

Gatovel components are designed to be installed independently from the Core.

Examples include:

* CLI
* Database
* Miau
* Future framework components

These components are distributed as separate Composer packages.

An application can therefore select the functionality it requires during installation.

For example:

```text
Gatovel Framework Installer

? Install CLI?       Yes
? Install Database?  Yes
? Install Miau?      No
```

Only the selected components are installed into the application.

This approach keeps the framework modular and allows individual components to evolve independently.

## Composer Packages

Optional components are managed through Composer.

The Gatovel Framework package provides the base framework, while additional functionality can be added through separate packages.

```text
gatovel/framework
       │
       ├── gatovel/cli
       ├── gatovel/database
       ├── gatovel/miau
       └── ...
```

The exact list of available components may evolve as the framework develops.

## Application Lifecycle

The HTTP lifecycle begins at the application's public entry point.

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

The application receives HTTP requests through the public entry point:

```text
public/index.php
```

The entry point loads the framework bootstrap and starts the application lifecycle.

### Bootstrap

The bootstrap process prepares the application environment before request processing begins.

It is responsible for loading Composer's autoloader and initializing the resources required by the application.

Optional components can integrate into the application during this process.

### Request

The `Request` component provides an abstraction over the incoming HTTP request.

It provides access to information such as:

* HTTP method
* URI
* Query parameters
* POST data
* Request input
* HTTP headers

Instead of accessing PHP superglobals directly throughout the application, application components can work with the `Request` abstraction.

### Routing

The Router determines which controller and method should handle the incoming request.

Routes can contain parameters.

For example:

```text
/users/{id}
```

When a request matches the route, the Router extracts the parameters and makes them available to the application.

The Router is also responsible for distinguishing between:

* Not Found
* Method Not Allowed
* Valid routes

### Controller Resolution

After a route is resolved, Gatovel determines the controller responsible for handling the request.

The controller is instantiated and validated before the requested method is executed.

The framework separates this process into stages so that each part of request resolution has a defined responsibility.

### Middleware

Middleware provides a mechanism for processing a request before it reaches the controller.

Multiple middleware components can be executed as a pipeline.

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

A middleware can:

* inspect a request;
* modify or validate request processing;
* stop the request;
* pass execution to the next middleware;
* process the resulting response.

### Controller

Controllers contain application-specific request handling logic.

The framework resolves the controller and invokes the method associated with the matched route.

The Core does not define the business logic of an application. That responsibility belongs to the application itself.

### View

The View component is responsible for rendering application views.

Views are separated from request routing and controller resolution, allowing presentation logic to remain independent from the framework's HTTP processing.

### Response

The `Response` component represents the result returned to the client.

Gatovel supports different response types, including:

* HTML responses
* JSON responses
* Redirect responses
* Custom HTTP status codes
* Custom HTTP headers

A response is sent to the client after the application lifecycle has completed.

## Separation of Responsibilities

Gatovel follows a separation of responsibilities between the framework and the application.

```text
Gatovel Core
     │
     ├── HTTP lifecycle
     ├── Routing
     ├── Middleware
     ├── Request / Response
     └── View system
     
Application
     │
     ├── Controllers
     ├── Models
     ├── Services
     ├── Routes
     └── Business logic
```

The framework provides the infrastructure required to run the application, while the application defines its own domain and business rules.

## Modularity

Modularity is a fundamental principle of Gatovel.

The Core should not depend on optional components such as Database or CLI.

Instead, optional functionality is added through independent packages.

```text
             ┌──────────────┐
             │ Gatovel Core │
             └──────┬───────┘
                    │
          ┌─────────┴─────────┐
          │                   │
     Optional Package   Optional Package
          │                   │
       Database              CLI
```

This architecture allows components to be:

* developed independently;
* versioned independently;
* installed only when required;
* replaced or extended without unnecessarily increasing the Core.

## Database Component

Database support is an optional Gatovel component.

It is intentionally separated from the Core so that the database layer can evolve independently from the framework's fundamental HTTP and application lifecycle.

The Database component is planned to provide database-related functionality through its own Composer package.

Its implementation and API are maintained independently from the Gatovel Core.

## Design Principles

The architecture of Gatovel is guided by several principles:

### Lightweight Core

The Core should provide only the functionality necessary to establish the framework's fundamental behavior.

### Modularity

Additional functionality should be provided through independent components whenever possible.

### Separation of Responsibilities

Each part of the framework should have a clear responsibility.

### Extensibility

Applications should be able to extend framework behavior without modifying the Core itself.

### Independent Evolution

Optional components should be able to evolve independently from the Core.

### Composer Integration

Composer is used as the package management mechanism for the framework and its optional components.
