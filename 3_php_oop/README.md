# 3_PHP_OOP
Works on my machine. ¯\\_(ツ)_/¯ 

## Structuur

De bedoeling van deze opdracht was om de hele webshop om te zetten naar een OOP structuur, waarin iedere pagina zijn eigen class krijgt, `extend`ed uit een parent class `htmlDoc`. Daarbovenop heb ik geprobeerd te werken met een MVC architectuur. Die verschillende onderdelen staan in de map `classes/` ondergebracht, onder respectievelijk `model/`, `view/` en `control/`. Daarnaast vind je nog wat extra maps, namelijk `config/`, gebruikt voor het configureren van pagina's en de login info voor de database, `database/` welke classes bevat voor het aanleggen van de database connectie en CRUD operaties, en `utils/` voor wat overblijfseltjes die het algehele schrijven van het project wat vergemakkelijkt hebben. Onder `assets` vind je (in `db`) het .sql file waarmee de bijbehorende database gegenereerd kan worden, en in `images/` staat nog een placeholder plaatje die ik overal tegenaan heb gegooid.

## Tests

Alle tests kunnen individueel gerund worden iets in de richting van `php tests/pagetests/loginpagetest.php`, of alles kan in een stuk door gerund worden door een andere file te runnen; `php tests/runtests.php`. 

## License

[MIT](https://choosealicense.com/licenses/mit/) license, Copyright © 2025 Rens van Eck.