# 4_PHP_AJAX
Works on my machine. ¯\\_(ツ)_/¯ 

## Installatie

Bij dit project ben ik gebruik gaan maken van composer. Derhalve moet er eerst het commando `composer install` gerund worden vanuit de project root, zodat alle dependencies geinstalleerd kunnen worden.

## Toevoegingen

Het project is gebruik gaan maken van AJAX. Deze JS scripts zijn te vinden in `assets/js`. Ze houden zich bezig met het reviewen van producten in de product info pages, en het muteren van de producten in de cart. Deze worden allemaal vanuit de `mainController` aangestuurd door een `ajaxController`. 

### Product reviews
Dit spreekt over de functies in `rating.js`. Ze besturen reviews op de product info pages die openenen bij het klikken op een van de producten in de webshop. De gemiddelde reviews worden weergegeven, zowel in het gemiddelde getal naast de sterren, alsmede door het aantal ingevulde sterren als de gebruiker nog geen review heeft geplaatst. Iedere gebruiker mag maar één review plaatsen.

### Cart
Er zijn verschillende "normale" page requests gespiegeld naar een AJAX method, zoals `addToCart`, `updateCart`, `removeFromCart` en `clearCart`. Dit maakt dat de cart direct gemuteerd kan worden zonder een volledige page reload. Ook zal het aantal items dat wordt aangegeven in het hyperlink menu bovenaan de pagina direct geüpdated worden.

## License

[MIT](https://choosealicense.com/licenses/mit/) license, Copyright © 2025 Rens van Eck.