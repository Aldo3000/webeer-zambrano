<?php

class Producto extends ActiveRecord
{

    protected static $tabla = 'productos';

    protected static $columnasDB = [
        'categoria_id',
        'marca_id',
        'sku',
        'nombre',
        'descripcion',
        'precio_original',
        'precio_actual',
        'stock',
        'imagen_principal'
    ];

    public $id;
    public $categoria_id;
    public $marca_id;
    public $sku;
    public $nombre;
    public $descripcion;
    public $precio_original;
    public $precio_actual;
    public $stock;
    public $imagen_principal;
    public $created_at;
    public $updated_at;
    // Propiedades utilizadas únicamente para mostrar
    // información relacionada obtenida mediante JOIN.
    public $categoria_nombre;
    public $marca_nombre;
    // Cantidad utilizada temporalmente para representar
    // la cantidad del producto dentro del carrito.
    // No corresponde a una columna de la tabla productos.
    public $cantidad;
    public $subtotal;
    // Indica si la cantidad solicitada en el carrito
    // supera el stock disponible actualmente.
    // No corresponde a una columna de la tabla productos.
    public $stock_insuficiente;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->categoria_id = '';
        $this->marca_id = '';
        $this->sku = '';
        $this->nombre = '';
        $this->descripcion = '';
        $this->precio_original = 0;
        $this->precio_actual = 0;
        $this->stock = 0;
        $this->imagen_principal = '';
        $this->cantidad = 0;
        $this->stock_insuficiente = false;
        $this->categoria_nombre = '';
        $this->marca_nombre = '';
        $this->marca_nombre = 0;
    }

    public function validar()
    {
        static::$errores = [];
        if (!$this->nombre) {
            static::$errores[] = 'El nombre es obligatorio.';
        }
        if (!$this->sku) {
            static::$errores[] = 'El SKU es obligatorio.';
        }
        if ($this->precio_actual <= 0) {
            static::$errores[] = 'El precio debe ser mayor a cero.';
        }
        if ($this->stock < 0) {
            static::$errores[] = 'El stock no puede ser negativo.';
        }
        if (!$this->categoria_id) {
            self::$errores[] = 'La categoría es obligatoria.';
        }
        if (!$this->marca_id) {
            self::$errores[] = 'La marca es obligatoria.';
        }

        if ($this->precio_original < 0) {
            self::$errores[] = 'El precio original no puede ser negativo.';
        }
        return static::$errores;
    }

    /**
     * Obtiene la categoría a la que pertenece
     * el producto actual.
     *
     * @return Categoria|null
     */
    public function categoria()
    {
        // Si el producto no tiene categoría asignada,
        // no hay nada que buscar.
        if (!$this->categoria_id) {
            return null;
        }

        // Buscar y devolver la categoría.
        return Categoria::find($this->categoria_id);
    }

    /**
     * Obtiene la marca a la que pertenece
     * el producto actual.
     *
     * @return Marca|null
     */
    public function marca()
    {
        // Si el producto no tiene marca asignada,
        // no hay nada que buscar.
        if (!$this->marca_id) {
            return null;
        }

        // Buscar y devolver la marca.
        return Marca::find($this->marca_id);
    }

    /**
     * Obtiene un producto junto con
     * su categoría y marca.
     */
    public static function findConRelaciones($id)
    {
        // Consulta el producto y obtiene
        // el nombre de su categoría y marca.
        $query = "SELECT
                productos.*,
                categorias.nombre AS categoria_nombre,
                marcas.nombre AS marca_nombre

              FROM productos

              LEFT JOIN categorias
                  ON productos.categoria_id = categorias.id

              LEFT JOIN marcas
                  ON productos.marca_id = marcas.id

              WHERE productos.id = $id
              LIMIT 1";

        // Ejecuta la consulta.
        $resultado = self::$db->query($query);

        // Si no existe el producto,
        // devuelve null.
        if (!$resultado->num_rows) {
            return null;
        }

        // Obtiene el registro de la consulta.
        $registro = $resultado->fetch_assoc();

        // Convierte los datos principales
        // en un objeto Producto.
        $producto = self::crearObjeto($registro);

        // Agrega los nombres de las relaciones
        // obtenidos mediante los JOIN.
        $producto->categoria_nombre = $registro['categoria_nombre'];
        $producto->marca_nombre = $registro['marca_nombre'];

        // Devuelve el producto completo.
        return $producto;
    }

    /**
     * Reduce el stock de un producto.
     *
     * La reducción solamente se realiza
     * si existe suficiente stock disponible.
     *
     * @param int $id
     * @param int $cantidad
     * @return bool
     */
    public static function reducirStock($id, $cantidad)
    {
        // Sanitizamos los valores numéricos.
        $id = (int) $id;
        $cantidad = (int) $cantidad;

        // La cantidad debe ser mayor a cero.
        if ($id <= 0 || $cantidad <= 0) {
            return false;
        }

        // Actualizamos el stock solamente
        // cuando existe suficiente inventario.
        $query = "UPDATE " . self::$tabla . "
              SET stock = stock - $cantidad
              WHERE id = $id
              AND stock >= $cantidad";

        // Ejecutamos la actualización.
        $resultado = self::$db->query($query);

        // Si la consulta falló, regresamos false.
        if (!$resultado) {
            return false;
        }

        // Verificamos que realmente se haya
        // actualizado un registro.
        return self::$db->affected_rows === 1;
    }
}
