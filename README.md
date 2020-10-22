> Being a senior developer means **I've already done** (one year ago)

The following text is in italian



DEV CHALLENGE
===============================

Il compito è stato risolto utilizzando sia PHP (v7.3.5) che NodeJs (v11.15.0).<br />
Lo script in PHP è eseguibile sia da browser che da Terminale.<br />
Lo script in NodeJs è raggiungibile sia con browser che con Postman.

Tempo di sviluppo totale: 1 giorno (11/05/2019)

# Esecuzione con PHP
## Posizionamento dello script
Posizionare la directory `TASK` in un percorso eseguibile dal web server (ad es: `/var/www`)

## Configurazione di Apache2 in locale
```
<VirtualHost *:80>
        ServerName task-php-node.local
        ServerAdmin webmaster@localhost

        ErrorLog ${APACHE_LOG_DIR}/error.task-php-node.log
        CustomLog ${APACHE_LOG_DIR}/access.task-php-node.log combined

        DocumentRoot /var/www/task-php-node/php
        <Directory /var/www/task-php-node/php/>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
        </Directory>
</VirtualHost>
```

Non dimenticate di abilitare l'indirizzo `task-php-node.local` nel file `/etc/hosts`

`$> sudo vi /etc/hosts`
e aggiungere la riga
`127.0.0.1               task-php-node.local`


## Esecuzione
### Browser
Per eseguire lo script da browser è necessario richiamare il file di index seguito dai seguenti filtri per cambiare l'output.<br />
L'output è in formato **JSON**.

Parametri:
* `customer` - Filtra la colonna dei customers
* `order` - Ordina i risultati secondo una colonna del csv
* `convert` - Converte la valuta (EUR, USD o GBP)
* `no-convert` - Non converte le valute

### Esempi
Filtra le transazioni del customer "1":
```
http://localhost/TASK/php/?customer=1
```

Filtra le transazioni del customer "2", le ordina per valore e converte i valori in Dollari:
```
http://localhost/TASK/php/?order=value&customer=2&convert=USD
```

Non effettua nessuna conversione di valuta:
```
http://localhost/TASK/php/?no-conversion
```

### Terminale
Da riga di comando è sufficiente richiamare il file di index seguito dai seguenti filtri per cambiare l'output.<br />
L'output è in formato **ARRAY**.
* `-c` o `--customer` - Filtra la colonna dei customers
* `-o` o `--order-by` - Ordina i risultati secondo una colonna del csv
* `-cc` o `--convert` - Converte la valuta (EUR, USD o GBP)
* `--no-convert` - Non converte le valute

### Esempi
Filtra le transazioni del customer "1":
``` shell
php index.php -c 1
```

Filtra le transazioni del customer "2", le ordina per valore e converte i valori in Dollari:
``` shell
php index.php -o value -c 2 -cc USD
```

Non effettua nessuna conversione di valuta:
``` shell
php index.php --no-conversion
```

# Esecuzione con NodeJs
Qualora necessario eseguire `npm install` per l'installazione dei pacchetti necessari.
Eseguire `npm start`, quindi aprire http://localhost:3000.
Lo script funzionerà nello stesso modo dell'esempio precedente.


# Note riguardo allo sviluppo
Nella versione in NodeJs non è stata implementata la finta conversione della valuta (lo si potrebbe fare semplicemente con `Math.random`).

-------------------------------------------
-------------------------------------------
> ### Obiettivo

> Dimostrare le tue competenze di programmazione ad oggetti e di preparazione
test automatici.

> ### Task

> Creare un semplice report che mostri le transazioni per un customer id
specificato come argomento da linea di comando.

> Il file data.csv contiene dati di esempio in varie valute, il report dovrà
mostrare i valori in EUR.

> Assumi che la base dati sia dinamica e che i dati arrivino da un database, il
csv è usato solo per semplicità di implementazione.

> Aggiungi il codice, i test e la documentazione che ritieni opportuna
(dockblock, commenti, README).  Non è necessario che l'applicazione utilizzi un
webservice di cambio valuta reale, un client finto che ritorna valori random o
fissi è sufficiente.

> Il codice fornito è solo un'indicazione di massima, non è necessario
utilizzarlo se non lo trovi utile. Se qualche requisito non ti è chiaro,
sentiti libero di improvvisare.

> Puoi utilizzare framework o librerie già pronte se lo ritieni opportuno. Se
utilizzi codice di terze parti ti consigliamo di utilizzare composer.

> ### Valutazione

> Il task sarà valutato sulla base del tuo utilizzo della programmazione OO,
della dependency injection (ove opportuno) e della leggibilità e manutenibilità.
In tal senso, la copertura con test automatici è fortemente incoraggiata.

> Ti invitiamo a curare tutti gli aspetti come se si trattasse di un progetto
pronto per andare in produzione. In tal senso, ad esempio, non consegnare file
o codice inutilizzato, soprattutto se proveniente da framework di terze parti.


> ### Alternativa linguaggio (PHP o Javascript)

> In alternativa, se ti trovi più a tuo agio in ambiente javascript/nodejs puoi
realizzare una semplice API che restituisca il report richiesto tramite chiamata
REST/HTTP.

> Anche (e soprattutto) in questo caso è indispensabile indicare nel README tutti i
passaggi necessari all'installazione e verifica.
