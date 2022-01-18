# Curso avanzado de PHP

## Comandos para ejecutar el proyecto
```
docker-compose up -d --build
```

En el php-container crear un enlace simbolico del directorio storage
```
cd /var/www/html/public
ln -s ../storage/app/public/ data
```
```
sudo vi /etc/crontab
sudo systemctl restart cron
```