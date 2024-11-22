![geniem-github-banner](https://cloud.githubusercontent.com/assets/5691777/14319886/9ae46166-fc1b-11e5-9630-d60aa3dc4f9e.png)

# Nuhe (Nuorten Helsinki)

## NPM

Asenna npm-paketit ajamalla teeman juuressa:

```
npm install
```

## Nimiavaruudet

Teeman nimiavaruus: `\Geniem\Theme`

## Kansiorakenne

Teema koostuu seuraavista hakemistoista.

- `/assets` - Scriptit ja tyylit.
- `/lang` - Lokalisaatiotiedostot.
- `/lib` - Backend-kirjastot, jotka noudattelee [PSR-4 Autoloader](https://www.php-fig.org/psr/psr-4/) määrityksiä.

## Globaalit PHP-apufunktiot

- ThemeController()-funktio, jolla voi hakea luokan instanssille lisätyn Controller-instanssin, esimerkiksi PostTypeController.
```php
// @codingStandardsIgnoreStart
$model = ThemeController()->get_class( 'PostTypeController' );
// @codingStandardsIgnoreEnd
```

- ModelController()-funktio, jolla voi hakea luokan instanssille lisätyn Model-instanssin, esimerkiksi Header-modelin.
```php
// @codingStandardsIgnoreStart
$model = ModelController()->get_class( 'Header' );
// @codingStandardsIgnoreEnd
```
