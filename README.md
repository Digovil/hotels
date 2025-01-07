# Sistema de Gestión Hotelera

## Descripción del Proyecto

El sistema desarrollado permite gestionar los hoteles de la compañía y asignarles tipos de habitaciones y acomodaciones de acuerdo a reglas predefinidas. Además, se asegura de validar restricciones de negocio como no exceder el número total de habitaciones y evitar duplicados.

---

## Estructura del Proyecto

### Tecnologías Utilizadas:
- **Backend**: PHP (Laravel 11)
- **Frontend Framework**: Nextjs 15 con Tailwind CSS
- **Base de Datos**: PostgreSQL 
- 
---

## Requerimientos del Sistema

- **PHP 8.2** o superior.
- **Composer** para gestión de dependencias del backend.
- **Node.js v20.9.0** o superior y npm para el frontend.
- **PostgreSQL 12**.

---

## Instalación y Configuración

### 1. Configuración del Backend
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/Digovil/hotels.git
   cd backend
   ```

2. Instalar dependencias:
   ```bash
   composer install
   ```

3. Configurar el archivo `.env`:
   ```env
   APP_NAME=HotelManager
   APP_ENV=local
   APP_KEY=
   APP_URL=http://localhost

   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=hotel_manager
   DB_USERNAME=<usuario>
   DB_PASSWORD=<contraseña>
   ```

4. Generar la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

5. Ejecutar migraciones y seeder:
   ```bash
   php artisan migrate --seed
   ```

6. Iniciar el servidor:
   ```bash
   php artisan serve
   ```
   
### 2. Configuración del Frontend
1. Acceder a la carpeta del frontend:
   ```bash
   cd frontend
   ```

2. Instalar dependencias:
   ```bash
   npm install
   ```

3. Configurar el archivo `.env.local`:
   ```env
   REACT_APP_API_URL=http://localhost:8000/api/v1
   ```

4. Iniciar el servidor de desarrollo:
   ```bash
   npm start
   ```

### 3. Base de Datos
1. Crear una base de datos PostgreSQL llamada `DecameronDB`.
2. Importar el dump proporcionado en el directorio DB.


---

## Estructura de la Base de Datos

#### `hotels`
Contiene información sobre los hoteles registrados.
- **Columnas:**
  - `hotel_id` (UUID): Identificador único del hotel.
  - `name` (VARCHAR): Nombre del hotel.
  - `address` (VARCHAR): Dirección del hotel.
  - `city` (VARCHAR): Ciudad donde se encuentra el hotel.
  - `tax_id` (VARCHAR): Identificación tributaria del hotel.
  - `total_rooms` (INTEGER): Número total de habitaciones disponibles en el hotel.
  - `created_at` (TIMESTAMP): Fecha de creación.
  - `updated_at` (TIMESTAMP): Fecha de última actualización.

#### `room_types`
Define los diferentes tipos de habitaciones disponibles.
- **Columnas:**
  - `room_type_id` (UUID): Identificador único del tipo de habitación.
  - `name` (VARCHAR): Nombre del tipo de habitación (Estándar, Junior, Suite).
  - `created_at` (TIMESTAMP): Fecha de creación.
  - `updated_at` (TIMESTAMP): Fecha de última actualización.

#### `accommodation_types`
Especifica los tipos de acomodaciones permitidas.
- **Columnas:**
  - `accommodation_type_id` (UUID): Identificador único del tipo de acomodación.
  - `name` (VARCHAR): Nombre del tipo de acomodación (Sencilla, Doble, Triple, Cuádruple).
  - `created_at` (TIMESTAMP): Fecha de creación.
  - `updated_at` (TIMESTAMP): Fecha de última actualización.

#### `hotel_rooms`
Relaciona los hoteles con los tipos de habitaciones y sus acomodaciones.
- **Columnas:**
  - `hotel_room_id` (UUID): Identificador único.
  - `hotel_id` (UUID): Referencia al hotel.
  - `room_type_id` (UUID): Referencia al tipo de habitación.
  - `accommodation_type_id` (UUID): Referencia al tipo de acomodación.
  - `quantity` (INTEGER): Cantidad de habitaciones de este tipo.
  - `created_at` (TIMESTAMP): Fecha de creación.
  - `updated_at` (TIMESTAMP): Fecha de última actualización.

#### `room_accommodation_rules`
Define las reglas que vinculan los tipos de habitaciones con los tipos de acomodaciones permitidas.
- **Columnas:**
  - `room_accommodation_rule_id` (UUID): Identificador único.
  - `room_type_id` (UUID): Referencia al tipo de habitación.
  - `accommodation_type_id` (UUID): Referencia al tipo de acomodación.
  - `created_at` (TIMESTAMP): Fecha de creación.
  - `updated_at` (TIMESTAMP): Fecha de última actualización.

### 2.2 Relaciones
- Un hotel puede tener varios tipos de habitaciones y sus respectivas acomodaciones.
- Cada tipo de habitación permite un subconjunto de tipos de acomodaciones, según las reglas definidas en `room_accommodation_rules`.

## 3. Endpoints RESTful
Cada entidad tiene operaciones CRUD con los siguientes endpoints:

### 3.1 Hoteles
- **Obtener un hotel:**
  ```http
  GET /hotels/{id}
  ```
- **Listar todos los hoteles:**
  ```http
  GET /hotels
  ```
- **Crear un hotel:**
  ```http
  POST /hotels/create
  ```
  **Body:**
  ```json
  {
    "name": "DECAMERON CARTAGENA",
    "address": "CALLE 23 58-25",
    "city": "CARTAGENA",
    "tax_id": "12345678-9",
    "total_rooms": 42
  }
  ```
- **Actualizar un hotel:**
  ```http
  PUT /hotels/update/{id}
  ```
- **Eliminar un hotel:**
  ```http
  DELETE /hotels/delete/{id}
  ```

### 3.2 Hotel Rooms
- **Obtener una configuración de habitación:**
  ```http
  GET /hotel_rooms/{id}
  ```
- **Listar configuraciones de habitaciones:**
  ```http
  GET /hotel_rooms
  ```
- **Crear una configuración de habitación:**
  ```http
  POST /hotel_rooms/create
  ```
  **Body:**
  ```json
  {
    "hotel_id": "uuid-hotel",
    "room_type_id": "uuid-room-type",
    "accommodation_type_id": "uuid-accommodation-type",
    "quantity": 25
  }
  ```
- **Actualizar una configuración de habitación:**
  ```http
  PUT /hotel_rooms/update/{id}
  ```
- **Eliminar una configuración de habitación:**
  ```http
  DELETE /hotel_rooms/delete/{id}
  ```

### 3.3 Tipos de Habitaciones
- **Obtener un tipo de habitación:**
  ```http
  GET /room_types/{id}
  ```
- **Listar todos los tipos de habitaciones:**
  ```http
  GET /room_types
  ```

### 3.4 Tipos de Acomodación
- **Obtener un tipo de acomodación:**
  ```http
  GET /accommodation_types/{id}
  ```
- **Listar todos los tipos de acomodaciones:**
  ```http
  GET /accommodation_types
  ```

### 3.5 Reglas de Acomodación
- **Obtener una regla:**
  ```http
  GET /room_accommodation_rules/{id}
  ```
- **Listar todas las reglas:**
  ```http
  GET /room_accommodation_rules
  ```

## 4. Reglas de Validación
1. **Cantidad de habitaciones:** La suma de habitaciones configuradas no puede exceder el total declarado en `total_rooms` del hotel.
2. **Hoteles duplicados:** No pueden existir dos hoteles con el mismo nombre y NIT.
3. **Configuraciones duplicadas:** No pueden repetirse combinaciones de `room_type_id` y `accommodation_type_id` para el mismo hotel.

## 5. Despliegue e Instalación
1. Clonar el repositorio del proyecto:
   ```bash
   git clone <repositorio>
   ```
2. Configurar la base de datos PostgreSQL y ejecutar el dump proporcionado.
3. Instalar las dependencias del backend (PHP):
   ```bash
   composer install
   ```
4. Configurar las variables de entorno en un archivo `.env`.
5. Iniciar el servidor del backend:
   ```bash
   php artisan serve
   ```
6. Instalar las dependencias del frontend:
   ```bash
   npm install
   ```
7. Iniciar el servidor del frontend:
   ```bash
   npm start
   ```




### Tablas Principales:
1. **hotels**: Registra los datos básicos de los hoteles.
2. **room_types**: Contiene los tipos de habitación disponibles (Estándar, Junior, Suite).
3. **accommodations**: Define las acomodaciones posibles para cada tipo de habitación.
4. **hotel_rooms**: Relaciona los hoteles con los tipos de habitaciones y sus acomodaciones.

### Relaciones:
- Un hotel puede tener múltiples tipos de habitaciones.
- Cada tipo de habitación tiene restricciones de acomodación.
- Se asegura que no se dupliquen registros dentro de un mismo hotel.

---

## Endpoints RESTful

### Hoteles
- **GET /api/hotels**: Listar todos los hoteles.
- **POST /api/hotels**: Crear un nuevo hotel.
- **PUT /api/hotels/{id}**: Actualizar un hotel.
- **DELETE /api/hotels/{id}**: Eliminar un hotel.

### Habitaciones
- **GET /api/hotels/{id}/rooms**: Listar habitaciones de un hotel.
- **POST /api/hotels/{id}/rooms**: Asignar tipos de habitaciones y acomodaciones.


---

## Figuras

### Diagrama de Base de Datos

![Diagrama de la base de datos](https://github.com/user-attachments/assets/2208e448-3b09-4479-974f-eb060eddb1e9)


### Aplicativo funcionando

Vista control de hoteles:


![image](https://github.com/user-attachments/assets/082a4992-30cc-480a-8b6c-cd8589d41c0e)


- Validaciones en hoteles (No se puede crear un hotel con nombre repetido y que el total de habitaciones sea menor o igual a cero)


![image](https://github.com/user-attachments/assets/238e7b8f-281d-4822-92d4-76d81f0ef4ca)


![image](https://github.com/user-attachments/assets/ac508b33-a229-4402-bc5d-8897c55e0122)



Vista de control de habitaciones:


![image](https://github.com/user-attachments/assets/1984ee28-32a7-42d6-b4a4-82cb3b85ab0d)


- Validación 1: No pueden haber tipos de habitaciones y acomodaciones repetidas)


![image](https://github.com/user-attachments/assets/8e3b5c76-5049-4764-9ec8-7833284683a7)


- Validación 2: La cantidad de habitaciones configuradas, no deben superar el máximo por hotel


![image](https://github.com/user-attachments/assets/2391fd52-61b0-4704-805c-8cbc746f28ba)

---



