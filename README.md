# Crud_Php

Crud simples construido com slimframework e reactjs

Para testar:

Necessário iniciar a imagem docker na pasta docker no Crud_api
ou iniciar uma conexão com banco de dados postgresql, criar um database chamado tasks e adicionar os dados abaixo (para a imagem docker também):

<h3>
CREATE TABLE tasks (
	id int4 NOT NULL GENERATED ALWAYS AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE),
	title varchar(255) NOT NULL,
	description text NOT NULL,
	status bool NOT NULL
);
</h3>

Rodar o server do php com: php -S localhost:8080 na pasta publica da api
e iniciar o projeto reactjs da pasta frontend com o <strong>npm start</strong>:


