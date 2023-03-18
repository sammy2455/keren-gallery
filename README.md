# Keren

Keren is a server for storing and optimizing images that you can use to host images in your projects and improve their display quality.

## How does it work?

Using Keren is very simple:

1. Clone this project on your computer.
2. Run the project on a host.
3. Upload the image you want to host on the server.
4. The image is automatically optimized to take up less space.
5. Keren provides you with an "ID" of the image on the server and two links: one to use the image on localhost and another to use it behind a reverse proxy.
6. Use the link you prefer to insert the image into your project.

## How to use Keren?

To use Keren, follow these steps:

1. Clone this project on your computer:

```sh
git clone https://github.com/sammy2455/keren-gallery.git
```

2. Run the project on a host:

```sh
cd keren-gallery
sudo docker compose build
sudo docker compose up -d
sudo docker compose exec php composer install
sudo docker compose exec php php artisan test
sudo docker compose exec php php artisan migrate:refresh --seed
```

3. Upload the image you want to host on the server:

**To upload an image, it is necessary to [_authenticate_](#authentication) and obtain the corresponding token.**

```bash
curl -F 'image=@/path/to/your/image.jpg' http://localhost:8800/api/v1/image/upload
```

4. Get the "ID" of the image on the server and the links to use it:

```json
{
    "success": true,
    "id": "fc460fe1-c047-4782-8800-32f8ab61f0f8",
    "internal_url": "http://localhost:8800/image/fc460fe1-c047-4782-8800-32f8ab61f0f8",
    "url": "https://media.hello4.one/image/fc460fe1-c047-4782-8800-32f8ab61f0f8"
}
```

5. Use the link you prefer to insert the image into your project.


### Authentication 


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

