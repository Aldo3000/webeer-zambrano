# Base de Datos

## Objetivo

La base de datos de WEBEER tiene como propósito almacenar toda la información necesaria para el funcionamiento del sistema de comercio electrónico.

Permitirá administrar usuarios, productos, categorías, pedidos y mensajes enviados desde el sitio web, garantizando la integridad de la información y la conservación del historial de compras.


## Reglas del Sistema

- Un usuario puede comprar como invitado o mediante una cuenta registrada.
- Los usuarios registrados podrán consultar su historial de pedidos.
- Los usuarios invitados no conservarán historial de compras.
- Cada usuario registrado tendrá una única dirección.
- Cada producto tendrá únicamente una imagen principal.
- Los pedidos conservarán el precio de cada producto al momento de la compra.
- Si una categoría es eliminada, los productos permanecerán activos sin categoría asignada.
- El sistema permitirá mostrar productos sin categoría dentro de una sección general.
- Los administradores podrán gestionar categorías, productos, pedidos y usuarios.



## Entidades

### Roles

Representa los tipos de usuario del sistema.

---

### Usuarios

Almacena la información de clientes y administradores.

---

### Categorías

Agrupa los productos comercializados por la tienda.

---

### Productos

Representa cada artículo disponible para venta.

---

### Pedidos

Almacena la información general de una compra.

---

### Detalle de Pedidos

Contiene los productos incluidos dentro de un pedido.

---

### Mensajes

Guarda los mensajes enviados desde el formulario de contacto.



## Relaciones




## Convenciones - Base de Datos

- Todas las tablas estarán en plural.
- Todas las columnas utilizarán snake_case.
- La llave primaria siempre será id.
- Las llaves foráneas utilizarán el formato nombre_tabla_id.
- Todas las tablas utilizarán el motor InnoDB.
- El juego de caracteres será utf8mb4.
- Se utilizará created_at y updated_at para registrar fechas cuando aplique.

## Tablas


## Decisiones de Diseño

### Compra como invitado

Se decidió permitir compras sin registro para reducir la fricción durante el proceso de compra y facilitar que nuevos clientes realicen pedidos rápidamente.

Los usuarios registrados tendrán acceso a un historial de compras y futuras funcionalidades relacionadas con su cuenta.

### Conservación del precio histórico

El precio del producto se almacena dentro del detalle del pedido para mantener el historial exacto de cada compra, incluso si el precio del producto cambia posteriormente.