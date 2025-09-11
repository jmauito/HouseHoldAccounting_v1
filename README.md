# HouseHoldAccounting_v1
Project for the registration of Ecuadorian invoices. It also allows you to record the values of deductible expenses.
This project is a concept idea for implementing a clean architecture using PHP without any framework.

## Prerequisites
Docker

## Installation
- Clone this repository. 
``` git clone https://github.com/jmauito/HouseHoldAccounting_v1.git ```
- Copy `.env.example` to `.env`.  
This file contains the default configuration for the project. You can modify these values.  
- Create the containers:
``` docker-compose up ```
This command will create the project and the databases.

## Project Structure
This project was developed in PHP 7.4. The database uses MySQL 8.
The credentials for the database are stored in the `.env` file.

## Architecture Layers
### Views
Contains web interfaces. These interfaces use Plates.
### Controllers
Endpoints for web interfaces. Using HTTP protocol based on REST.
### Application Services
Contains the high-level services for the application to handle the use cases.
### Dao
Objects to access the tables for entities.
### Domain
Entities from the domain.
### Infrastructure
Generic classes to use external packages and libraries through the facade pattern.
### Lib
Generic classes to use in the domain.
### public
Entry point to the application.
### init-db
SQL scripts to initialize the database.
### db_data
This folder will be created when you execute docker-compose for the first time. It stores the database files.

## MIT License

Copyright (c) [2025] [Mauricio Zárate]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS.

---

# HouseHoldAccounting_v1 (Español)

Proyecto para el registro de facturas ecuatorianas. También permite registrar los valores de gastos deducibles.
Este proyecto es una idea conceptual para implementar una arquitectura limpia usando PHP sin ningún framework.

## Requisitos Previos
Docker

## Instalación
- Clona este repositorio. 
``` git clone https://github.com/jmauito/HouseHoldAccounting_v1.git ``` 
- Copia `.env.example` a `.env`.  
Este archivo contiene la configuración por defecto del proyecto. Puedes modificar estos valores.  
- Crea los contenedores:
``` docker-compose up ```
Este comando creará el proyecto y las bases de datos.

## Estructura del Proyecto
Este proyecto fue desarrollado en PHP 7.4. La base de datos utiliza MySQL 8.
Las credenciales de la base de datos están almacenadas en el archivo `.env`.

## Capas de la Arquitectura
### Views
Contiene las interfaces web. Estas interfaces usan Plates.
### Controllers
Endpoints para las interfaces web. Usando el protocolo HTTP basado en REST.
### Application Services
Contiene los servicios de alto nivel para que la aplicación maneje los casos de uso.
### Dao
Objetos para acceder a las tablas de las entidades.
### Domain
Entidades del dominio.
### Infrastructure
Clases genéricas para usar paquetes y librerías externas a través del patrón fachada.
### Lib
Clases genéricas para usar en el dominio.
### public
Punto de entrada a la aplicación.
### init-db
Scripts SQL para inicializar la base de datos.
### db_data
Esta carpeta se creará cuando ejecutes docker-compose por primera vez. Almacena los archivos de la base de datos.

## Licencia MIT

Copyright (c) [2025] [Mauricio Zárate]

Por la presente se concede permiso, libre de cargos, a cualquier persona que obtenga una copia
de este software y los archivos de documentación asociados (el "Software"), para tratar
en el Software sin restricción, incluyendo sin limitación los derechos
de usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar y/o vender
copias del Software, y para permitir a las personas a quienes se les proporcione el Software
hacerlo, sujeto a las siguientes condiciones:

El aviso de copyright anterior y este aviso de permiso deberán incluirse en todas
las copias o partes sustanciales del Software.

EL SOFTWARE SE PROPORCIONA "TAL CUAL", SIN GARANTÍA DE NINGÚN TIPO, EXPRESA O
IMPLÍCITA, INCLUYENDO PERO NO LIMITADO A LAS GARANTÍAS DE COMERCIALIZACIÓN,
IDONEIDAD PARA UN PROPÓSITO PARTICULAR Y NO INFRACCIÓN. EN NINGÚN CASO LOS
AUTORES O TITULARES DEL COPYRIGHT SERÁN RESPONSABLES POR NINGUNA RECLAMACIÓN, DAÑO U OTRA
RESPONSABILIDAD, YA SEA EN UNA ACCIÓN DE CONTRATO, AGRAVIO O DE OTRA MANERA, QUE SURJA DE,
FUERA DE O EN CONEXIÓN CON EL SOFTWARE O EL USO U OTRO TIPO DE ACCIONES EN EL
SOFTWARE.