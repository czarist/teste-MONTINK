# ERP E-commerce PHP Puro

Aplicação backend de e-commerce, simulação carrinho de compras, cupons, variações de produtos, estoque e checkout.

---

## Instalação

### 1. Clone o projeto

git clone \<URL\_DO\_REPOSITORIO>
cd \<PASTA\_DO\_PROJETO>

### 2. Instale as dependências via Composer

composer install

### 3. Configure o banco de dados e SMTP

Crie um arquivo `.env` na raiz do projeto com as seguintes configurações:

#### Banco de Dados:

DB\_CONNECTION=mysql
DB\_HOST=localhost
DB\_PORT=3306
DB\_DATABASE=nome\_do\_banco
DB\_USERNAME=usuario
DB\_PASSWORD=senha

#### SMTP (envio de e-mails):

MAIL\_HOST=dominio.smtp.com.br
MAIL\_PORT=587
MAIL\_USERNAME=
MAIL\_PASSWORD=
MAIL\_FROM\_ADDRESS=
MAIL\_FROM\_NAME=Teste
MAIL\_ENCRYPTION=tls

### 4. Execute a migração para criar as tabelas

composer migrate

### 5. (Opcional) Popule o banco com dados iniciais de teste

composer seed

### 6. Inicie o servidor local

composer start

A aplicação ficará disponível em:

[http://localhost:8000](http://localhost:8000)

---

## Modelagem do Banco de Dados

### Tabelas principais:

**produtos**

* id
* nome
* preco
* created\_at
* status (enum: 'active', 'inactive')

**variacoes**

* id
* produto\_id (FK → produtos.id)
* atributo
* valor

**estoque**

* id
* produto\_id (FK → produtos.id)
* variacao\_id (FK → variacoes.id)
* quantidade

**pedidos**

* id
* subtotal
* frete
* total
* cep
* endereco
* status
* created\_at

**pedido\_itens**

* id
* pedido\_id (FK → pedidos.id)
* produto\_id (FK → produtos.id)
* quantidade
* preco\_unitario
* variacao\_snapshot
* variacao\_id (FK → variacoes.id)
* preco

**cupons**

* id
* codigo
* desconto\_percentual
* valor\_minimo
* validade
* status (enum: 'active', 'inactive')

---

## Documentação da API

### Carrinho

**Adicionar item ao carrinho**
POST `/api/carrinho/adicionar`

**Alterar quantidade de item**
POST `/api/carrinho/alterar`

**Remover item do carrinho**
POST `/api/carrinho/remover`

### Checkout

**Finalizar pedido**
POST `/api/checkout/finalizar`

### Produtos

**Criar produto**
POST `/api/produto`

**Atualizar produto**
PUT `/api/produto/{id}`

**Excluir produto**
DELETE `/api/produto/{id}`

### Variações de Produtos

**Listar variações de um produto**
GET `/api/produto/{produto_id}/variacoes`

**Criar variação**
POST `/api/produto/{produto_id}/variacoes`

**Excluir variação**
DELETE `/api/variacoes/{variacao_id}`

### Cupons

**Criar cupom**
POST `/api/cupons`

**Atualizar cupom**
PUT `/api/cupons/{id}`

**Excluir cupom**
DELETE `/api/cupons/{id}`

**Validar cupom**
POST `/api/cupom/validar`

### Pedidos (Webhook)

**Atualizar status do pedido**
POST `/api/webhook/pedido`

---

## Dependências

* PHP 8.x
* PDO MySQL
* Composer
* Dotenv
* Bramus Router
* PHPMailer

---
