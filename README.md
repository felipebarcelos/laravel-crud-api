## Amar Assist - CRUD:

O projeto Amar Assit - CRUD, tem por objetivo guardar informações de contatos (Nome, E-mail e Telefone).

- API REST com autenticação (passport) e testes automatizados.

## Como instalar o projeto:

- Premissas:
Docker e composer instalados.

Após fazer o clone do projeto no repositório do github, acessa a pasta do projeto (LaravelCRUD) e digite o seguinte comando para atualizar o projeto, baixar as dependências:

- composer update

Após executar o comando acima, duplique o arquivo .env.example e renomea para .env (raiz do projeto).

Abra o arquivo .env (criado no passo anterior) e nas configurações de banco de dados, insira conforme abaixo (observação: As senhas só estão expostas porque é um teste):

- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=amar_assist
- DB_USERNAME=root
- DB_PASSWORD=root

Agora, acesse a pasta laradock.

Dentro da pasta laradock, digite o seguinte comando para instalar as imagens docker e subir os containers:

- sudo docker-compose up -d nginx mysql phpmyadmin

Após executar o comando acima, acesse o phpmyadmin:
- http://localhost:1011
- Servidor: mysql
- Pass: root
- User: root

Crie a tabela: amar_assist com a colação utf8mb4_unicode_ci.

Após criar a tabela acima, acesse o container para executarmos as migrations que criará a tabela contatos:

- sudo docker-compose exec --user=laradock workspace bash

Na sequência, execute o comando:

- php artisan migrate

O comando acima irá criar nossas tabelas de migrations e contatos.

## Registrando usuário na API (Postman):

** Importante:
- Talvez seja necessário a criação de um token pessoal para pode registrar novos usuários com o comando abaixo:

- php artisan passport:client --personal

Primeiro é necessário registrar para conseguir obter o token de acesso:

- http://localhost:8889/api/register [POST]

Parametros: name, email, password e c_password.

Após fazer o registro do usuário na aplicação é necessário fazer o login na API:

- http://localhost:8889/api/login [POST]

Parametros: email e password. Será retornado o token.

## Consumindo a API (Postman):

Digite a seguinte URL (GET) para retornar os contatos:

- http://localhost:8889/contatos

No HEADER é necessário informar dois parametros, conforme abaixo:

'headers' => [

    'Accept' => 'application/json',

    'Authorization' => 'Bearer '.$accessToken,

]

Para demais rotas da API, utilize a tabela descrita no último tópico deste tutorial.

$accessToken será substituido pelo TOKEN retornado no momento em que fazemos o login na API, exemplo de Authorization:

Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDYyOGI1ODUxZWI2ZTExNWNhNzZlZWYxY2Q3NGJkYjdkZTJhZDc4NWJjZDA2YjNmNWFmY2U4NGU1MzFiMzExZTE5MzNkM2YyZGVlYmYxZGIiLCJpYXQiOjE2Mjc5NDE1NDAuMTUzNDQsIm5iZiI6MTYyNzk0MTU0MC4xNTM0NDMsImV4cCI6MTY1OTQ3NzU0MC4xNDYxNjEsInN1YiI6IjMiLCJzY29wZXMiOltdfQ.qXBjp98Ciz66kalMQK4gYJl7Sz7C4KBQpOIzuGSIMIkzQycAzgKHL3Bhy_pRk_LEer3_ETpCG2YzAFxWG6RaZ2qZMfgjIV6hzrXWSYcJ-y1dMSpK5W7rXqpy6r3BzGhFVUeYhz1eRj5jcm8D7Zdd3sdFuM8RRPF872NgTIPzk7--DMs98PeMQ0MEcwQXindIy81NnezncC9AtLCS8T79fk5HPC8l_IVAkbi0X9k-1Lmhxwqt0ErifQAjtFGeE4ot3bm3W9v6Q3ao4gshpLy_BEvEZw62F32Pw8sYhgoZJHohWXH7tUZPwN_E1VHAAStVl01yoQtLEqxtlv3uryjxqR5gWGY226ywh4d44YyDJPmZBF_i8q_1gUJgW27V0LfDk_7e5P7oR0oC0-BbuLz-9Q3LEmPCgHbrLYfe2xM18md2XZ8nRL83GWZGraaZm97yh6NrsZLmM-2Q6IfBSOFfwJplv8QEe0n9K4DDIAM7GN2iomEQcQOUB3q9_gutkbl2Xk5O4RXLZAh0RRXgH7wOJUQOcF0kaxYQtxPcx6wHZv3Z4vOHiker5hya8l1p49Fyr_rgIS7lkL1vXlE1Q6GSFZk8jYBWJTaWUu-wrqBhzrIRLY26Swk7kAUTTmyWHMTE-YTyw0jy-yL2M2uLGE_sZJIDPspjHkgd8Ne00ynQyk8

Pronto, agora pode utilizar a vontade! Crie novos contatos, edite e remova os contatos.

## Rotas utilizadas padrão REST:

| Method    | URI                         | Name             | Action                                          | Middleware   |
|-----------|-----------------------------|------------------|-------------------------------------------------|--------------|
| GET|HEAD  | /                           |                  | Closure                                         | web          |
| GET|HEAD  | api/contatos                | contatos.index   | App\Http\Controllers\ContatoController@index    | api auth:api |
| POST  |   | api/contatos                | contatos.store   | App\Http\Controllers\ContatoController@store    | api auth:api |
| GET|HEAD  | api/contatos/create         | contatos.create  | App\Http\Controllers\ContatoController@create   | api auth:api |
| GET|HEAD  | api/contatos/{contato}      | contatos.show    | App\Http\Controllers\ContatoController@show     | api auth:api |
| PUT|PATCH | api/contatos/{contato}      | contatos.update  | App\Http\Controllers\ContatoController@update   | api auth:api |
| DELETE    | api/contatos/{contato}      | contatos.destroy | App\Http\Controllers\ContatoController@destroy  | api auth:api |
| GET|HEAD  | api/contatos/{contato}/edit | contatos.edit    | App\Http\Controllers\ContatoController@edit     | api auth:api |
| POST      | api/login                   |                  | App\Http\Controllers\ContatoController@login    | api          |
| POST      | api/register                |                  | App\Http\Controllers\ContatoController@register | api          |


