# Web de Ca la Sisqueta

Aquesta és la web del projecte cooperatiu i associatiu de Ca La Sisqueta de
Gramenet del Besòs. Es basa en el projecte [Wordpress
Heroku](https://github.com/mhoofman/wordpress-heroku) per allotjar una
instal·lació wordpress a servidors de Heroku.

## Instal·lació

El que segueix són els passos d'instal·lació per Mac OS X.

Abans de començar la instal·lació cal que el vostre sistema tingui Xcode i
Homebrew instal·lats. Executant la següent comanda us apareixerà un diàleg del
sistema que descarregarà Xcode.

```bash
$ xcode-select --install
```

Un cop instal·lat, podeu instal·lar Hombrew:

```bash
$ ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

### Postgres

Descarregueu i instal·leu [Postgres.app](http://postgresapp.com/).

Per arrancar Postgres només cal que executeu l'aplicació.

### PHP-FPM

Tot seguit instal·leu php-fpm amb suport per postgres amb les comandes següents:

```bash
$ brew tap josegonzalez/php
$ brew tap homebrew/dupes
$ brew install php56 --with-debug --without-apache --with-imap --with-postgresql=/Applications/Postgres.app/Contents/Versions/<num_versió>
```

Per arrancar o aturar PHP-FPM amb les comandes:

```bash
$ php56-fpm start
```

```bash
$ php56-fpm stop
```

Es pot recarregar la configuració sense necessitat d'aturar el procés amb:

```bash
$ php56-fpm reload
```

### Nginx

Executeu la següent comanda al vostre terminal:

```bash
$ brew install nginx
```

Tot seguit creeu els directoris on allotjar els virtual hosts:

```bash
$ mkdir /usr/local/etc/php/5.6/php-fpm.conf /usr/local/etc/php/5.6/php-fpm.conf
```

El directori `sites-available` conté els arxius de configuració de tots els
virtual hosts que tingueu, en el nostre cas el de Ca la Sisqueta, mentre
`sites-enabled` conté soft-links apuntant a aquests. D'aquesta manera es pot
habilitar o deshabilitar un sol virtual host creant o eliminant el seu soft-link.

Ara podeu copiar l'arxiu de configuració de nginx de l'arrel del vostre repositori local:

```bash
$ cp nginx.conf.example /usr/local/etc/nginx/nginx.conf
```

i l'arxiu de configuració de Ca la Sisqueta:

```bash
$ cp calasisqueta.local.example /usr/local/etc/nginx/sites-available/calasisqueta.local
```

Finalment, cal habilitar el virtual host pel nostre lloc web:

```bash
$ ln -s /usr/local/etc/nginx/sites-available/calasisqueta.local /usr/local/etc/nginx/sites-enabled/calasisqueta.local
```

Podeu utilitzar les comandes següents per gestionar el servidor:

```bash
$ sudo nginx            # arrancar
$ sudo nginx -s stop    # aturar
$ sudo nginx -s reload  # recarregar
```

### /etc/hosts

Per poder accedir a la web a través de `calasisqueta.local` modifiqueu l'arxiu `/etc/hosts` com s'indica a continuació:

```bash
$ sudo vim /etc/hosts
```

Afegiu `127.0.0.1       calasisqueta.local` al capdamunt de la llista i guardeu els canvis.

Tot seguit executeu la següent comanda per què els canvis es facin efectius:

```bash
$ sudo killall -HUP mDNSResponder
```
