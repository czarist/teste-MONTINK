# ERP E-commerce PHP Puro

![PHP Version](https://img.shields.io/badge/PHP-8.x-%23777BB4)
![License](https://img.shields.io/badge/License-MIT-blue)

Um sistema de e-commerce completo com gerenciamento de produtos, carrinho de compras, cupons de desconto e processamento de pedidos.

## Tabela de Conteúdos

- [Funcionalidades](#funcionalidades)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
- [API Endpoints](#api-endpoints)
- [Contribuição](#contribuição)
- [Licença](#licença)

## Funcionalidades
 
- Gerenciamento de produtos com variações
- Carrinho de compras
- Sistema de cupons de desconto
- Controle de estoque
- Processamento de pedidos
- Notificações por e-mail

## Pré-requisitos

- PHP 8.2 ou superior
- MySQL 5.7+ ou MariaDB 10.2+
- Composer 2.0+
- Extensões PHP: PDO, OpenSSL, Mbstring

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/erp-ecommerce-php.git
cd erp-ecommerce-php
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o ambiente:
```bash
cp .env.example .env
```

## Configuração

Edite o arquivo `.env` com suas configurações:

```ini
# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nome_banco
DB_USERNAME=usuario
DB_PASSWORD=senha

# E-mail
MAIL_MAILER=smtp
MAIL_HOST=mail.exemplo.com
MAIL_PORT=587
MAIL_USERNAME=email@exemplo.com
MAIL_PASSWORD=senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@exemplo.com"
MAIL_FROM_NAME="ERP E-commerce"
```

Execute as migrações:
```bash
composer migrate
```

(Opcional) Popule o banco com dados de teste:
```bash
composer seed
```

Inicie o servidor:
```bash
composer start
```

Acesse: http://localhost:8000

## Estrutura do Banco de Dados

### Diagrama Entidade-Relacionamento

```
+-------------+       +---------------+       +-------------+
|  produtos   |       |   variacoes   |       |   estoque   |
+-------------+       +---------------+       +-------------+
| PK id       |<----->| PK id         |<----->| PK id       |
| nome        |       | FK produto_id |       | FK produto_id
| descricao   |       | atributo      |       | FK variacao_id
| preco       |       | valor         |       | quantidade  |
| status      |       +---------------+       +-------------+
| created_at  |
+-------------+
       ↑
       |
+-------------+       +---------------+
|   pedidos   |       | pedido_itens  |
+-------------+       +---------------+
| PK id       |<----->| PK id         |
| subtotal    |       | FK pedido_id  |
| frete       |       | FK produto_id |
| total       |       | quantidade    |
| cep         |       | preco_unitario|
| endereco    |       | variacao_snap |
| status      |       | variacao_id   |
| created_at  |       +---------------+
+-------------+

+-------------+
|   cupons    |
+-------------+
| PK id       |
| codigo      |
| desconto_%  |
| valor_min   |
| validade    |
| status      |
+-------------+
```

## API Endpoints

### Carrinho

| Método | Endpoint                  | Descrição                          |
|--------|---------------------------|------------------------------------|
| POST   | /api/carrinho/adicionar   | Adiciona item ao carrinho          |
| POST   | /api/carrinho/alterar     | Altera quantidade de item          |
| POST   | /api/carrinho/remover     | Remove item do carrinho            |

### Checkout

| Método | Endpoint                  | Descrição                          |
|--------|---------------------------|------------------------------------|
| POST   | /api/checkout/finalizar   | Finaliza o pedido                  |

### Produtos

| Método | Endpoint                          | Descrição                          |
|--------|-----------------------------------|------------------------------------|
| GET    | /api/produtos                     | Lista todos produtos               |
| POST   | /api/produtos                     | Cria novo produto                  |
| GET    | /api/produtos/{id}                | Obtém detalhes do produto          |
| PUT    | /api/produtos/{id}                | Atualiza produto                   |
| DELETE | /api/produtos/{id}                | Remove produto                     |

### Cupons

| Método | Endpoint                  | Descrição                          |
|--------|---------------------------|------------------------------------|
| POST   | /api/cupons/validar       | Valida um cupom de desconto        |

## Contribuição

1. Faça um fork do projeto
2. Crie uma branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## Licença

Distribuído sob licença MIT. Veja `LICENSE` para mais informações.

---

