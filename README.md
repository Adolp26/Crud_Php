# Crud_Php

Crud construido com slimframework e reactjs

Para testar:

Necessário iniciar a imagem docker na pasta docker no Crud_api com <strong>docker-compose up</strong>; <br>
Ou iniciar uma conexão com banco de dados postgresql, criar um database chamado tasks e adicionar os dados abaixo (para a imagem docker também):

<h5>
CREATE TABLE tasks (
  id serial PRIMARY KEY,
  title varchar(255) NOT NULL,
  description text NOT NULL,
  status boolean NOT NULL
);
</h5>
<br>
Foi utilizado como padrão: 
<br>
<br>
<strong>
      POSTGRES_USER: 'username'
      <br>
      POSTGRES_PASSWORD: 'password'
      <br>
      POSTGRES_DB: 'tasks'
      <br>
      PORT:5432
</strong>
<br>
<br>

Rodar o server do php com: php -S localhost:8080 na pasta publica da api
e iniciar o projeto reactjs da pasta frontend com o <strong>npm start</strong>:


