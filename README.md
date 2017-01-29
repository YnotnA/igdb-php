# igdb-php
PHP Facade/Wrapper for the IGDB API (Mashape)

## Usage

```php
// Set your API KEY
define('API_KEY', 'Your_Mashape_Key');

// Instancie client
$client = new YnotnA\Igdb\IgdbApi(API_KEY);

// Get games by search.
$games = $client->getGames('mario', array('name'), 10, 0);
```
