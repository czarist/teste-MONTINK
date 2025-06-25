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

## Endpoints da API

### Carrinho

POST `/api/carrinho/adicionar` - Adiciona produto ao carrinho
POST `/api/carrinho/alterar` - Altera quantidade de item no carrinho
POST `/api/carrinho/remover` - Remove item do carrinho

### Checkout

POST `/api/checkout/finalizar` - Finaliza o pedido com os dados do carrinho

### Produtos

POST `/api/produto` - Cria um novo produto
PUT `/api/produto/{id}` - Atualiza um produto
DELETE `/api/produto/{id}` - Exclui um produto

### Variações

GET `/api/produto/{produto_id}/variacoes` - Lista variações de um produto
POST `/api/produto/{produto_id}/variacoes` - Cria uma nova variação
DELETE `/api/variacoes/{variacao_id}` - Exclui uma variação

### Cupons

POST `/api/cupons` - Cria um cupom
PUT `/api/cupons/{id}` - Atualiza um cupom
DELETE `/api/cupons/{id}` - Exclui um cupom
POST `/api/cupom/validar` - Valida cupom com base no subtotal do carrinho

### Pedidos (Webhook de atualização)

POST `/api/webhook/pedido` - Atualiza o status do pedido

---

## Tecnologias utilizadas

* PHP 8.x 
* PDO MySQL
* Composer 
* Dotenv 
* Bramus Router 
* PHPMailer 

---
