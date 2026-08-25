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

    /**
     * Valida los datos de dirección
     * del usuario.
     */
    public function validarDireccion()
    {
        static::$errores = [];

        if (!$this->calle) {
            static::$errores[] =
                'La calle es obligatoria.';
        }

        if (!$this->numero) {
            static::$errores[] =
                'El número es obligatorio.';
        }

        if (!$this->colonia) {
            static::$errores[] =
                'La colonia es obligatoria.';
        }

        if (!$this->municipio) {
            static::$errores[] =
                'El municipio es obligatorio.';
        }

        if (!$this->estado) {
            static::$errores[] =
                'El estado es obligatorio.';
        }

        if (!$this->codigo_postal) {
            static::$errores[] =
                'El código postal es obligatorio.';
        }

        return static::$errores;
    }

    /**
     * Obtiene el usuario mediante su ID.
     *
     * @param int $id
     * @return Usuario|null
     */
    public static function buscarPorId($id)
    {
        $id = (int) $id;

        if ($id <= 0) {
            return null;
        }

        return self::find($id);
    }

    /**
     * Verifica si un correo electrónico
     * ya pertenece a otro usuario.
     *
     * @param string $correo
     * @param int $usuarioId
     * @return bool
     */

    public static function correoExisteEnOtroUsuario($correo, $usuarioId)
    {
        $correo = trim($correo);
        $usuarioId = (int) $usuarioId;

        if ($correo === '') {
            return false;
        }

        $usuario = self::where(
            'correo',
            $correo
        );

        // No existe ningún usuario con ese correo.
        if (!$usuario) {
            return false;
        }

        // Si el correo pertenece al mismo usuario
        // que estamos editando, sí puede conservarlo.
        if ((int) $usuario->id === $usuarioId) {
            return false;
        }

        // El correo pertenece a otro usuario.
        return true;
    }

    /**
     * Guarda únicamente los datos de dirección
     * del usuario.
     */
    public function guardarDireccion()
    {
        return $this->guardar();
    }

    /**
     * Determina si el usuario tiene
     * una dirección de envío completa.
     */
    public function tieneDireccion()
    {
        return
            !empty($this->calle) &&
            !empty($this->numero) &&
            !empty($this->colonia) &&
            !empty($this->municipio) &&
            !empty($this->estado) &&
            !empty($this->codigo_postal);
    }
}
