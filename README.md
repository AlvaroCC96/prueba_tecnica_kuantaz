### Prueba Técnica Kuantaz 
#### Álvaro Lucas Castillo Calabacero
---

#### Instalación

- Clonar repositorio
- Copiar .env.example a .env
- Ejecutar: "composer install"
- Ejecutar: "npm install && npm run dev"
- Ejecutar: "php artisan serve"
- Probar endpoint con POSTMAN o curl

```console
curl --location --request POST 'http://127.0.0.1:8000/api/get_data'
```

#### Test
```console
php artisan test --filter BeneficiosServiceTest
```


```console
php artisan test
```


#### Postman y Swagger.

El archivo POSTMAN se encuentra dentro del proyecto y el link de la documentación Swagger es el siguiente:

[Link documentación Swagger](https://app.swaggerhub.com/apis/ALVAROLUCASCC96/Prueba_Tecnica_Kuantaz/1.0.0)


#### Consideraciones o datos asumidos.
- La ruta en api.php, está creada solo con la finalidad de la evaluación.
- En los endpoints entregados faltan 2 datos
  - En el resultado esperado la variable view , se declara por defecto True
  - En el resultado esperado, no se expresa el total por año, pero si esta implementado en esta propuesta

---
##### Autor: Álvaro Lucas Castillo Calabacero
##### Fecha: 30/04/2024
---
