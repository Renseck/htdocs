# 5_PHP_API
Works on my machine. ¯\\_(ツ)_/¯ 

## Installatie

Eerst moet het commando `composer install` gerund worden vanuit de project root, zodat alle dependencies geinstalleerd kunnen worden.

## Toevoegingen

We hebben een API toegevoegd aan het webproject. Deze geeft de mogelijkheid om via `index.php?action=api&type=[json][xml][html]&function=...` data op te vragen van de server wat betreft de producten die verkrijgbaar zijn op de webshop.

## API Endpoints

De mogelijke endpoints van de API staan hieronder:

- Get all products: `?action=api&type=[json|xml|html]&function=all`
- Get product by ID: `?action=api&type=[json|xml|html]&function=item&id=[product_id]`
- Get product by keyword: `?action=api&type=[json|xml|html]&function=item&search=[name]`

Het zoeken van het product by keyword gebruikt de productModel implementatie van `searchProduct()`, die zowel op de naam als de beschrijving van producten zoekt. Dit stelt gebruikers beter in staat om de `product_id` van gewenste items op te halen en vervolgens specifiekere zoekopdrachten te doen dan schieten met hagel.

## Test

De map `/tests/` heeft een extra file `apitest.php` gekregen. Deze fungeert niet op de manier dat de normale test files dat doen. Deze test file kan direct via de browser bekeken worden, en dan kan men direct zien wat de (formatted) responses van de verscheidene endpoints zijn, alsmede hoe die response te krijgen. 

## Notes
Ik besef dat mijn huidige structuur van het opslaan van enkel de path van de product images en niet de images zelf op dit moment onhandig is geworden. Wanneer een API request een product opvraagt, krijgt de client enkel de naam van de afbeelding, die voor hen uiteraard volstrekt waardeloos is. Daar staat tegenover dat een afbeelding meesturen op een API response ook weer niet geweldig lijkt. Op dit punt moet ik echter ook bekennen dat dat oplossen me niet erg raakt.


## License

[MIT](https://choosealicense.com/licenses/mit/) license, Copyright © 2025 Rens van Eck.