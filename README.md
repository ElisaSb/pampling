# PRUEBA PAMPLING

## Guía

Para arrancar el proyecto, seguid los siguientes pasos:

* Instalar [Docker](https://docs.docker.com/engine/install/ubuntu/), [Docker-compose](https://docs.docker.com/compose/install/) y el comando _make_:
```console
$ sudo apt install make
```

* Lanzar los siguientes comandos dentro de la raíz del proyecto:
```console
$ make build
$ make install
$ make ssh-be
$ composer install
$ php bin/console doctrine:migrations:migrate
```
* Al acceder desde un navegador a "http://127.0.7.14" podremos ver el mensaje --> "Hola, Mundo!" en la pantalla.

***

### Datos sobre la prueba

Las características de la prueba son las siguientes:

* Se encuentra configurada con Docker.
* PHP: 7.4
* Symfony: 5
* Para acceder a la documentación del proyecto, haga clic [aquí](http://127.0.7.14/api/doc).
  * Las rutas de la api RESTful son las que se encuentran separadas por el tag "api user".
