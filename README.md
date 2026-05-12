# Bookstore Eloquent Lab

Proyecto Laravel que modela una libreria en linea usando Eloquent ORM. El dominio incluye catalogo, autores, categorias, etiquetas, clientes, direcciones, ordenes, items y resenas.

## Cumplimiento del laboratorio

| Requisito | Implementacion |
|---|---|
| Minimo 10 tablas con migraciones | 13 migraciones en `database/migrations` |
| `up()` y `down()` correctos | Cada migracion crea su tabla en `up()` y la elimina en `down()` |
| Un modelo Eloquent por tabla | 13 modelos en `app/Models` |
| `$fillable` y `$casts` | Definidos en cada modelo |
| Minimo 5 relaciones bidireccionales | `Publisher-Book`, `Book-Author`, `Book-Category`, `Book-Tag`, `Customer-Address`, `Customer-Order`, `Order-OrderItem`, `Book-Review`, entre otras |
| 5 consultas Eloquent | Archivo `app/Examples/EloquentQueries.php` |
| Eager Loading con justificacion | Primera consulta usa `with()` y comenta por que evita N+1 |
| Seeder con 10,000+ registros | `DatabaseSeeder` genera mas de 29,000 registros coherentes |

## Tablas

- `publishers`
- `categories`
- `authors`
- `tags`
- `books`
- `book_authors`
- `book_categories`
- `book_tags`
- `customers`
- `addresses`
- `orders`
- `order_items`
- `reviews`

## Relaciones principales

- Una editorial tiene muchos libros; un libro pertenece a una editorial.
- Un libro tiene muchos autores y un autor tiene muchos libros.
- Un libro tiene muchas categorias y una categoria tiene muchos libros.
- Un libro tiene muchas etiquetas y una etiqueta tiene muchos libros.
- Un cliente tiene muchas direcciones y una direccion pertenece a un cliente.
- Un cliente tiene muchas ordenes y una orden pertenece a un cliente.
- Una orden tiene muchos items y un item pertenece a una orden.
- Un libro tiene muchas resenas y una resena pertenece a un libro.

## Como correrlo

Desde la carpeta del proyecto:

```bash
cd bookstore-eloquent-lab
composer install
cp .env.example .env
php artisan key:generate
```

Luego configure en `.env` la conexion MySQL o PostgreSQL, como muestra el material:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookstore
DB_USERNAME=root
DB_PASSWORD=
```

O PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bookstore
DB_USERNAME=postgres
DB_PASSWORD=
```

Cree la base de datos `bookstore` en el motor elegido y ejecute:

```bash
php artisan migrate --seed
```

Si solo quiere correr migraciones, el comando base mostrado en el material es:

```bash
php artisan migrate
```

Si las tablas ya existen y se quiere regenerar todo desde cero:

```bash
php artisan migrate:fresh --seed
```

## Consultas Eloquent incluidas

El archivo `app/Examples/EloquentQueries.php` contiene consultas con las operaciones mostradas en el material:

- Insercion con `create`.
- Busqueda por ID con `find`.
- Filtros con `where`.
- Ordenamiento con `orderBy`.
- Lectura con `get`.
- Actualizacion con `save`.
- Eliminacion con `delete`.
- Acceso a relaciones como atributo del objeto.
- Eager Loading justificado para evitar N+1.
