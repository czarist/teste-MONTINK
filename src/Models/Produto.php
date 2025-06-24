<?php
namespace App\Models;

class Produto extends Model
{
    public static function listar()
    {
        $stmt = static::pdo()->query("SELECT * FROM produtos");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function buscar($id)
    {
        $stmt = static::pdo()->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function inserir($nome, $preco)
    {
        $stmt = static::pdo()->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
        $stmt->execute([$nome, $preco]);
        return static::pdo()->lastInsertId();
    }

    public static function atualizar($id, $nome, $preco)
    {
        $stmt = static::pdo()->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
        $stmt->execute([$nome, $preco, $id]);
    }
}
