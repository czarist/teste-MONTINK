-- PRODUTOS
INSERT INTO produtos (nome, preco, status) VALUES
('Camiseta Algodão', 49.90, 'active'),
('Calça Jeans Slim', 129.90, 'active'),
('Tênis Esportivo', 199.90, 'active'),
('Jaqueta Corta-Vento', 159.90, 'active'),
('Boné Aba Reta', 39.90, 'active'),
('Meia Cano Alto', 19.90, 'active'),
('Relógio Digital', 249.90, 'active'),
('Mochila Impermeável', 119.90, 'active'),
('Carteira Couro', 89.90, 'active'),
('Óculos de Sol Polarizado', 149.90, 'active'),
('Camiseta DryFit', 59.90, 'active'),
('Calça Térmica', 109.90, 'active'),
('Sapato Social', 299.90, 'active'),
('Camisa Polo', 79.90, 'active'),
('Chinelo Slide', 59.90, 'active'),
('Jaqueta de Couro', 399.90, 'active'),
('Tênis Casual', 179.90, 'active'),
('Bermuda Moletom', 69.90, 'active'),
('Mochila Executiva', 199.90, 'active'),
('Bolsa Feminina', 249.90, 'active');

-- VARIAÇÕES (tamanho ou cor)
INSERT INTO variacoes (produto_id, atributo, valor) VALUES
(1, 'Tamanho', 'P'),
(1, 'Tamanho', 'M'),
(1, 'Tamanho', 'G'),
(2, 'Tamanho', '38'),
(2, 'Tamanho', '40'),
(2, 'Tamanho', '42'),
(3, 'Cor', 'Preto'),
(3, 'Cor', 'Branco'),
(4, 'Tamanho', 'M'),
(4, 'Tamanho', 'G'),
(5, 'Cor', 'Vermelho'),
(5, 'Cor', 'Preto'),
(6, 'Tamanho', 'Único'),
(7, 'Cor', 'Azul'),
(8, 'Cor', 'Preto'),
(9, 'Cor', 'Marrom'),
(10, 'Cor', 'Preto'),
(11, 'Tamanho', 'G'),
(12, 'Tamanho', 'M'),
(13, 'Tamanho', '42');

-- ESTOQUE
INSERT INTO estoque (produto_id, quantidade, variacao_id) VALUES
(1, 50, 1),
(1, 30, 2),
(1, 20, 3),
(2, 40, 4),
(2, 35, 5),
(2, 25, 6),
(3, 60, 7),
(3, 40, 8),
(4, 20, 9),
(4, 10, 10),
(5, 80, 11),
(5, 50, 12),
(6, 100, 13),
(7, 30, 14),
(8, 50, 15),
(9, 70, 16),
(10, 40, 17),
(11, 20, 18),
(12, 15, 19),
(13, 10, 20);

-- CUPONS
INSERT INTO cupons (codigo, desconto_percentual, valor_minimo, validade, status) VALUES
('DESCONTO10', 10, 100.00, '2025-12-31', 'active'),
('BLACKFRIDAY', 20, 200.00, '2025-11-30', 'active'),
('FRETEGRATIS', 0, 50.00, '2025-10-15', 'active'),
('PROMO15', 15, 150.00, '2025-08-31', 'active'),
('OUTLET30', 30, 300.00, '2025-09-30', 'active');

-- PEDIDOS
INSERT INTO pedidos (subtotal, frete, total, cep, endereco, status) VALUES
(199.80, 20, 219.80, '12345-678', 'Rua A, 100, Centro, Cidade A', 'concluido'),
(79.90, 15, 94.90, '98765-432', 'Av B, 200, Bairro B, Cidade B', 'aguardando_pagamento'),
(159.90, 0, 159.90, '11223-445', 'Rua C, 300, Cidade C', 'concluido'),
(249.90, 15, 264.90, '55667-889', 'Av D, 400, Cidade D', 'concluido'),
(89.90, 20, 109.90, '99887-776', 'Rua E, 500, Cidade E', 'aguardando_pagamento');

-- PEDIDO_ITENS
INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario, preco, variacao_id) VALUES
(1, 1, 2, 49.90, 99.80, 2),
(1, 5, 1, 100.00, 100.00, 11),
(2, 4, 1, 79.90, 79.90, 9),
(3, 3, 1, 159.90, 159.90, 7),
(4, 7, 1, 249.90, 249.90, 14),
(5, 9, 1, 89.90, 89.90, 16);
