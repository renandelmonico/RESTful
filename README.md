# README #

# Estrutura RESTful para projetos

## Requisitos

* Versao do PHP >= 7.0
* Composer

## Instalacao

* Faca o clone do repositorio
* Crie uma pasta chamada config na raiz do projeto e um arquivo dentro dela com o nome config.json. Configure-o de acordo com suas preferencias/necessidades.
* Atualize as libs do composer

## config.json

```json
{
    "auth" : {
        "key": "supersecretkeyyoushouldnotcommittogithub"
    },
    "database" : {
        "host" : null,
        "port" : null,
        "username" : null,
        "password" : null,
        "database" : null
    }
}
```