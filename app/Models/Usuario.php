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
        'codigo_postal',
        'activo'
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
    public $activo;
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
        $this->activo = $args['activo'] ?? null;
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

    /**
     * Obtiene todos los usuarios registrados.
     *
     * @return array
     */
    public static function obtenerTodos()
    {
        $query = "SELECT *
              FROM " . static::$tabla . "
              ORDER BY created_at DESC";

        return static::consultaSQL($query);
    }

    /**
     * Busca usuarios desde administración.
     *
     * Permite buscar por nombre,
     * apellido o correo.
     *
     * @param string $busqueda
     * @return array
     */
    public static function buscarAdmin($busqueda = '')
    {
        $busqueda = trim($busqueda);

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE 1 = 1";

        if ($busqueda !== '') {

            $busqueda =
                static::$db->escape_string(
                    $busqueda
                );

            $query .= "
            AND (
                nombre LIKE '%{$busqueda}%'
                OR apellido LIKE '%{$busqueda}%'
                OR correo LIKE '%{$busqueda}%'
            )
        ";
        }

        $query .= "
        ORDER BY created_at DESC
    ";

        return static::consultaSQL($query);
    }

    /**
     * Obtiene los usuarios con rol de cliente.
     *
     * @return array
     */
    public static function obtenerClientes()
    {
        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE rol_id = 3
              ORDER BY created_at DESC";

        return static::consultaSQL($query);
    }

    /**
     * Busca clientes desde administración.
     *
     * @param string $busqueda
     * @return array
     */
    public static function buscarClientes($busqueda = '')
    {
        $busqueda = trim($busqueda);

        $query = "SELECT *
              FROM " . static::$tabla . "
              WHERE rol_id = 3";

        if ($busqueda !== '') {

            $busqueda =
                static::$db->escape_string(
                    $busqueda
                );

            $query .= "
            AND (
                nombre LIKE '%{$busqueda}%'
                OR apellido LIKE '%{$busqueda}%'
                OR correo LIKE '%{$busqueda}%'
            )
        ";
        }

        $query .= "
        ORDER BY created_at DESC
    ";

        return static::consultaSQL($query);
    }

    /**
     * Actualiza los datos administrativos
     * de un cliente.
     *
     * @param int $usuarioId
     * @param array $datos
     * @return bool
     */
    public static function actualizarCliente($usuarioId, $datos)
    {
        $usuarioId = (int) $usuarioId;
        if ($usuarioId <= 0) {
            return false;
        }
        $nombre =
            static::$db->escape_string(
                trim($datos['nombre'] ?? '')
            );
        $apellido =
            static::$db->escape_string(
                trim($datos['apellido'] ?? '')
            );
        $correo =
            static::$db->escape_string(
                trim($datos['correo'] ?? '')
            );
        $telefono =
            static::$db->escape_string(
                trim($datos['telefono'] ?? '')
            );
        $calle =
            static::$db->escape_string(
                trim($datos['calle'] ?? '')
            );
        $numero =
            static::$db->escape_string(
                trim($datos['numero'] ?? '')
            );
        $colonia =
            static::$db->escape_string(
                trim($datos['colonia'] ?? '')
            );
        $municipio =
            static::$db->escape_string(
                trim($datos['municipio'] ?? '')
            );
        $estado =
            static::$db->escape_string(
                trim($datos['estado'] ?? '')
            );
        $codigoPostal =
            static::$db->escape_string(
                trim($datos['codigo_postal'] ?? '')
            );
        $query = "UPDATE " . static::$tabla . "
              SET
                nombre = '{$nombre}',
                apellido = '{$apellido}',
                correo = '{$correo}',
                telefono = '{$telefono}',
                calle = '{$calle}',
                numero = '{$numero}',
                colonia = '{$colonia}',
                municipio = '{$municipio}',
                estado = '{$estado}',
                codigo_postal = '{$codigoPostal}'
              WHERE id = {$usuarioId}
              LIMIT 1";
        return static::$db->query($query);
    }

    /**
     * Cambia el estado de una cuenta.
     */
    public static function cambiarEstado($usuarioId, $activo)
    {
        $usuarioId = (int) $usuarioId;
        $activo = (int) $activo;

        if ($usuarioId <= 0 || !in_array($activo, [0, 1], true)) {
            return false;
        }

        $query = "UPDATE " . static::$tabla . "
              SET activo = {$activo}
              WHERE id = {$usuarioId}
              AND rol_id = 3
              LIMIT 1";

        return static::$db->query($query);
    }
}
