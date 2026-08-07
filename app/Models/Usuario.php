<?php

/**
 * Modelo encargado de representar
 * los usuarios del sistema.
 */
class Usuario extends ActiveRecord
{
    /**
     * Tabla asociada.
     */
    protected static $tabla = 'usuarios';
    /**
     * Columnas existentes en la base de datos.
     */
    protected static $columnasDB = [
        'rol_id',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'password_hash',
        'calle',
        'numero',
        'colonia',
        'municipio',
        'estado',
        'codigo_postal'
    ];

    /**
     * Propiedades del modelo.
     */
    public $id;
    public $rol_id;
    public $nombre;
    public $apellido;
    public $correo;
    public $telefono;
    public $password_hash;
    public $calle;
    public $numero;
    public $colonia;
    public $municipio;
    public $estado;
    public $codigo_postal;
    public $created_at;
    public $updated_at;
    /**
     * Constructor.
     */
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->rol_id = $args['rol_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->password_hash = $args['password_hash'] ?? '';
        $this->calle = $args['calle'] ?? '';
        $this->numero = $args['numero'] ?? '';
        $this->colonia = $args['colonia'] ?? '';
        $this->municipio = $args['municipio'] ?? '';
        $this->estado = $args['estado'] ?? '';
        $this->codigo_postal = $args['codigo_postal'] ?? '';
        $this->created_at = $args['created_at'] ?? null;
        $this->updated_at = $args['updated_at'] ?? null;
    }

    public function validar()
    {

        static::$errores = [];
        if (!$this->nombre) {
            static::$errores[] =
                'El nombre es obligatorio.';
        }

        if (!$this->apellido) {
            static::$errores[] =
                'El apellido es obligatorio.';
        }

        if (!$this->correo) {
            static::$errores[] =
                'El correo es obligatorio.';
        }

        if (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            static::$errores[] =
                'El correo no es válido.';
        }

        if (!$this->telefono) {
            static::$errores[] =
                'El teléfono es obligatorio.';
        }

        if (strlen($this->password_hash) < 8) {
            static::$errores[] =
                'La contraseña debe tener al menos 8 caracteres.';
        }

        return static::$errores;
    }
}
