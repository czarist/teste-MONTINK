<?php

namespace App\Models;

class Produto extends Model
{
    public static function listar(): array
    {
        $stmt = static::pdo()->query("SELECT * FROM produtos WHERE status = 'active'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function listarPaginado(int $limit, int $offset): array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM produtos WHERE status = 'active' LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function buscarVariacaoPorId(int $variacao_id): ?array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM variacoes WHERE id = ?");
        $stmt->execute([$variacao_id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }


    public static function buscarVariacoes(int $produto_id): array
    {
        $stmt = static::pdo()->prepare(
            "SELECT v.id, v.atributo, v.valor, e.quantidade as estoque
        FROM variacoes v
        LEFT JOIN estoque e ON v.id = e.variacao_id
        WHERE v.produto_id = ?"
        );
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function total(): int
    {
        $stmt = static::pdo()->query("SELECT COUNT(*) FROM produtos WHERE status = 'active'");
        return (int) $stmt->fetchColumn();
    }

    public static function excluir(int $id): void
    {
        $stmt = static::pdo()->prepare("UPDATE produtos SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function buscar(int $id): ?array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public static function inserir(string $nome, float $preco): int
    {
        $pdo = static::pdo();

        $stmt = $pdo->prepare("SELECT id, status FROM produtos WHERE nome = ?");
        $stmt->execute([$nome]);
        $produtoExistente = $stmt->fetch();

        if ($produtoExistente) {
            if ($produtoExistente['status'] == 1) {
                throw new \Exception("Já existe um produto ativo com esse nome.");
            } else {
                $stmtUpdate = $pdo->prepare("UPDATE produtos SET preco = ?, status = 1 WHERE id = ?
            ");
                $stmtUpdate->execute([$preco, $produtoExistente['id']]);
                return (int) $produtoExistente['id'];
            }
        }

        $stmtInsert = $pdo->prepare("INSERT INTO produtos (nome, preco, status) VALUES (?, ?, 1)");
        $stmtInsert->execute([$nome, $preco]);
        return (int) $pdo->lastInsertId();
    }

    public static function atualizar(int $id, string $nome, float $preco): void
    {
        $pdo = static::pdo();

        $stmt = $pdo->prepare("SELECT id FROM produtos WHERE nome = ? AND id != ?");
        $stmt->execute([$nome, $id]);
        if ($stmt->fetch()) {
            throw new \Exception("Já existe outro produto com esse nome.");
        }

        $stmtUpdate = $pdo->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
        $stmtUpdate->execute([$nome, $preco, $id]);
    }
}
