# McWebPanel (Versión Desarrollo)
McWebPanel es un panel de administración de Software Libre exclusivo para Servidores Minecraft, creado para PHP8 para ser utilizado en servidores Apache y Nginx.

Diseñado pensando en usuarios con conocimientos limitados a la hora de crear servidores y con el objetivo de utilizar el menor número de dependencias posibles.

Una interfaz de usuario responsive bajo Bootstrap para una navegación fácil para los usuarios.

![PanelGif](https://user-images.githubusercontent.com/34619567/93478584-1ec69800-f8fc-11ea-9319-51a590a30313.gif)

## Comenzando 🚀

Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu servidor.



### Pre-requisitos 📋

Estos son los requisitos para que McWebPanel funcione

```
Sistemas Operativos:
Debian 12.x | Debian 13.x
Ubuntu Server 22.04 LTS | 24.04 LTS | 26.04 LTS

Servidor Web:
Apache2 / Nginx

Versiones PHP compatibles:
PHP 8.0 | PHP 8.1 | PHP 8.2 | PHP 8.3 | PHP 8.4 | PHP 8.5

OpenJDK             (Maquina Virtual Java)
screen              (GNU Screen)
php-mbstring        (Libreria strings php)
php-zip             (Libreria Zip php)
php-cli             (Libreria cli php)
php-json            (Librerua json php)
zip                 (Info-ZIP)
unzip               (De-archiver)
gawk                (GNU gawk)
wget                (GNU Wget)
tar                 (GNU tar)
gzip                (GNU gzip)
git                 (GIT)
pigz                (PIGZ Mark Adler)
```

### Instalación 🔧

Guía paso a paso para realizar la instalación

Actualizar Servidor

```
sudo apt update
sudo apt upgrade
```

Instalar Paquetes Requisitos (Ubuntu Server / Debian)

```
sudo apt install apache2 php libapache2-mod-php default-jdk screen php-cli php-json gawk wget tar gzip git zip unzip pigz
```

Instalar

```
Descargar:
wget https://github.com/DEV-MCWEBPANEL/McWebPanel/archive/refs/tags/v0.24.zip

Descomprimir:
unzip v0.24.zip

Eliminar index.html por defecto de apache:
sudo rm /var/www/html/index.html

Copiar a la carpeta Apache:
sudo cp -r McWebPanel-0.24/. /var/www/html/

Cambiar Usuario Archivos:
sudo chown -R www-data:www-data /var/www/html/
```

Configurar zona horaria (Ubuntu y Debian 11)
```
sudo dpkg-reconfigure tzdata
```

Editar Servicio Apache (SOLO Ubuntu 26.04)
```
sudo systemctl edit apache2

Añadir los parámetros:
[Service]
ProtectProc=default
ProcSubset=all
PrivateTmp=no
MemoryDenyWriteExecute=no

Guardar Archivo NANO
Ctrl + o y luego pulsar enter
Ctrl + x salir de nano

Actualizar Cambios en servicios Systemd
sudo systemctl daemon-reexec

Recargar Systemd
sudo systemctl daemon-reload

```

Configurar Directorio Apache
- Requerido para Proteger Carpetas.
- Requerido para configuración Subir archivos.


```
Editar configuración por defecto:
sudo vim /etc/apache2/sites-available/000-default.conf

Añadir la siguiente configuración:

ServerTokens Prod
ServerSignature Off
TraceEnable Off

<VirtualHost *:80>

ServerAdmin webmaster@localhost
DocumentRoot /var/www/html

<Directory /var/www/html>
Options -Indexes
AllowOverride All
Require all granted
</Directory>

ErrorLog ${APACHE_LOG_DIR}/error.log
CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>

```

Generar Carpeta Maven (Requerido para Compilar Spigot)

```
sudo mkdir /var/www/.m2
sudo chown -R www-data:www-data /var/www/.m2
```

Reiniciar Apache para aplicar cambios

```
sudo systemctl restart apache2
```

Abre el navegador y entra en el panel

```
http://la-ip-del-servidor
```

## Construido con 🛠️

* [Bootstrap](https://getbootstrap.com/) - Bootstrap
* [jQuery](https://jquery.com/) - jQuery
* [PHP](https://www.php.net/) - PHP
* [xPaw Library](https://github.com/xPaw/PHP-Minecraft-Query) - PHP Minecraft Query library

## Colaboradores ✒️

* **Bluewolf** - *Tester Oficial* - [BluewolfYT](https://github.com/BluewolfYT)

## Licencia 📄

Este proyecto está bajo la Licencia (GPLv3) - mira el archivo [LICENSE](LICENSE) para detalles
