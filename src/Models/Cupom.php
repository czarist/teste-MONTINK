<?php
namespace App\Models;

class Cupom extends Model
{
    public static function buscarPorCodigo($codigo)
    {
        $stmt = static::pdo()->prepare("SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([$codigo]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
