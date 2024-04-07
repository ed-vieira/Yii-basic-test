<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

<br>
<br>

#### Projeto
> https://yii-test.edvieira.com.br/site/docs


Execução do projeto em localhost:

A aplicação foi criada utilizando Docker fazendo uso da imagem oficial recomendada de acordo com a versão da linguagem
https://github.com/yiisoft/yii2-docker 


Na pasta root do projeto executar os comandos docker **(Docker Desktop)**: 
```
docker compose up -d  
```

Este comando fará o "build" da imagem com suas dependências

Além da imagem oficial o docker-compose contem a imagem do MySql 8, como foi indicado para o teste, acompanha um banco de dados de desenvolvimento, um banco de dados para execução de testes e o PhpMyAdmin para acesso aos bancos de dados 


Documentação da API: (Swagger 2.0 - em razão de compatibilidade com a versão uilizada da linguagem e as respectivas bibliotecas)
https://swagger.io/specification/v2/


Para a execução do projeto:

Instalar as dependências

```
docker compose run --rm php composer install
```

Definir as permissões de arquivos para a aplicação processar as requisições e gerar os logs:

```
docker compose run --rm php chown www-data:www-data -R  /app/runtime
```

```
docker compose run --rm php chown www-data:www-data -R  /app/web
```
Executando migrations

```
docker compose run --rm php yii migrate
```

Criar usuário em modo desenvolvimento/produção 

```
docker compose run --rm php yii create-user --name=Administrador  --username=admin --password=admin 
```

#### Swagger:
> http://localhost:8000/site/docs

#### Autenticação: 
> http://localhost:8000/api/v1/auth/token

#### Clientes:
> http://localhost:8000/api/v1/customers

#### Produtos: 
> http://localhost:8000/api/v1/products

#### Produtos por cliente: 
> http://localhost:8000/api/v1/customer/{customerId}/products


Executando migrations para executar testes
```
docker compose run --rm  php  tests/bin/yii migrate
```

Criar usuário em modo teste

```
docker compose run --rm  php tests/bin/yii  create-user --name=Administrador  --username=admin --password=admin 
```
* Os testes foram escritos utilizando este usuário

Executando testes

```
docker compose run --rm  php  vendor/bin/codecept run
```


