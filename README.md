<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>


### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    
You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches



A aplicação foi criada utilizando Docker fazendo uso da imagem oficial recomendada de acordo com a versão da linguagem
https://github.com/yiisoft/yii2-docker 

Na pasta root do projeto executar o comando: 
docker compose up -d

Este comando fará o "build" da imagem com suas dependências


Além da imagem oficial o docker-compose contem a imagem do MySql 8, como foi indicado para o teste, acompanha um banco de dados de desenvolvimento, um banco de dados para execução de testes e o PHPMyAdmin para acesso aos bancos de dados 


Documentação da API: (Swagger 2.0 - em razão de compatibilidade com a versão uilizada da linguagem e as respectivas bibliotecas)
https://swagger.io/specification/v2/

http://localhost:8000/site/docs


Executando migrations
docker compose run --rm  php   yii migrate

Executando migrations para executar testes
docker compose run --rm  php  tests/bin/yii migrate

Criar usuário em modo desenvolvimento/produção 
docker compose run --rm  php yii  create-user --name=Administrador  --username=admin --password=admin 

Criar usuário em modo teste
docker compose run --rm  php tests/bin/yii  create-user --name=Administrador  --username=admin --password=admin 
* Os testes foram escritos utilizando este usuário

Executando testes
docker compose run --rm  php  vendor/bin/codecept run


