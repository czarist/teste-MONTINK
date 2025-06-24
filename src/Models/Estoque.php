<?php
namespace App\Models;

class Estoque extends Model
{
    public static function atualizar($produto_id, $variacao, $quantidade)
    {
        $stmt = static::pdo()->prepare("SELECT id FROM estoque WHERE produto_id = ? AND variacao = ?");
        $stmt->execute([$produto_id, $variacao]);
        $result = $stmt->fetch();

        if ($result) {
            $stmt = static::pdo()->prepare("UPDATE estoque SET quantidade = ? WHERE id = ?");
            $stmt->execute([$quantidade, $result['id']]);
        } else {
            $stmt = static::pdo()->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
            $stmt->execute([$produto_id, $variacao, $quantidade]);
        }
    }

    public static function reduzirEstoque($produto_id, $variacao, $quantidade)
    {
        $stmt = static::pdo()->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE produto_id = ? AND variacao = ?");
        $stmt->execute([$quantidade, $produto_id, $variacao]);
    }
}
