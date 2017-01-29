<?php

namespace YnotnA\Igdb;

use Unirest\Request;
use YnotnA\Igdb\Exception\InvalidFieldException;
use YnotnA\Igdb\Exception\InvalidAPiKeyException;
use YnotnA\Igdb\Exception\TooManyRequestsException;
use YnotnA\Igdb\Exception\BadRequestException;
use YnotnA\Igdb\Exception\NotFoundException;

class IgdbApi
{
    /**
     * The base URL to the API mashape.
     */
    const ENDPOINT = 'https://igdbcom-internet-game-database-v1.p.mashape.com/%s/';

    /**
     * The default field.
     */
    const DEFAULT_FIELD = '*';

    /**
     * The field separator.
     */
    const FIELD_SEPARATOR = ',';

    /**
     * The valid request code.
     */
    const VALID_REQUEST_CODE = 200;

    /**
     * The bad request error code.
     */
    const BAD_REQUEST_CODE = 400;

    /**
     * The bad request not found error code.
     */
    const BAD_REQUEST_NOT_FOUND = 404;

    /**
     * The bad api key error code.
     */
    const BAD_API_KEY_CODE = 403;

    /**
     * The too many request error code.
     */
    const TOO_MANY_REQUEST_CODE = 429;

    /**
     * Api Key (Mashape)
     * @var string
     */
    private $apiKey;

    
    /**
     * Class construct
     * @param string $apiKey Api Key Mashape
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function request($info, $query, $id = null)
    {
        // build url.
        if (empty($id)) {
            $url = sprintf(static::ENDPOINT . '?%s', $info, http_build_query($query));
        } else {
            $url = sprintf(static::ENDPOINT . '%s?%s', $info, $id, http_build_query($query));
        }
        
        // build header.
        $headers = array(
            'Accept'        => 'application/json',
            'X-Mashape-Key' => $this->apiKey
        );

        // Send request.
        $response = Request::get($url, $headers, $query);

        // Get return code and check if error.
        if($response->code !== static::VALID_REQUEST_CODE) {
            switch ($response->code) {
            case static::BAD_API_KEY_CODE:
                throw new InvalidApiKeyException($response);
                break;
            case static::BAD_REQUEST_NOT_FOUND:
                throw new NotFoundException($response);
                break;
            case static::TOO_MANY_REQUEST_CODE:
                throw new TooManyRequestsException($response);
                break;
            case static::BAD_REQUEST_CODE:
            default:
                throw new BadRequestException($response);
                break;
            }
        }

         if (!empty($id) && is_array($response->body)) {
            return $response->body[0];
         } else {
            return $response->body;
         }
    }

    /**
     * Build fields
     * @param  array  $fields Fields
     * @return string         Fields separate with FIELD_SEPARATOR.
     */
    private function fieldsBuilder(array $fields)
    {
        if (empty($fields)) {
            $fields[] = static::DEFAULT_FIELD;
        }

        return implode(static::FIELD_SEPARATOR, $fields);
    }

    /**
     * Get character information.
     * 
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @return object             Response body
     */
    public function getCharacters(array $fields = array(), $limit = 10)
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
        );

        return $this->request('characters', $query);
    }

    /**
     * Get character information by id.
     * 
     * @param  integer $id    Character ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getCharacterById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('characters', $query, $id);
    }

    /**
     * Get companies information.
     * 
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @param  integer    $offset Offset
     * @return object             Response body
     */
    public function getCompanies(array $fields = array(), $limit = 10, $offset = 0)
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
            'offset'    => $offset,
        );

        return $this->request('companies', $query);
    }

    /**
     * Get companie information by id.
     * 
     * @param  integer $id    Companie ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getCompanieById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('companies', $query, $id);
    }

    /**
     * Get franchies information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getFranchises(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('franchises', $query);
    }

    /**
     * Get franchise information by id.
     * 
     * @param  integer $id    Franchise ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getFranchiseById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('franchises', $query, $id);
    }

    /**
     * Get game modes information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getGameModes(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('game_modes', $query);
    }

    /**
     * Get game mode information by id.
     * 
     * @param  integer $id    GameMode ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getGameModeById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('game_modes', $query, $id);
    }

    /**
     * A list of games with specified fields and filters.
     * 
     * @param  string     $search Search
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @param  integer    $offset Offset
     * @param  string     $order  Order
     * @return object             Response body
     */
    public function getGames($search, array $fields = array(), $limit = 10, $offset = 0, $order = null)
    {
        $query = array(
            'search'    => $search,
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
            'offset'    => $offset,
            'order'     => $order,
        );

        return $this->request('games', $query);
    }

    /**
     * Get game information by id.
     * 
     * @param  integer $id    Game ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getGameById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('games', $query, $id);
    }

    /**
     * Get genres information.
     * 
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @param  integer    $offset Offset
     * @return object             Response body
     */
    public function getGenres(array $fields = array(), $limit = 10)
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
        );

        return $this->request('genres', $query);
    }

    /**
     * Get genre information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getGenreById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('genres', $query, $id);
    }

    /**
     * Get keywords information.
     * 
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @param  integer    $offset Offset
     * @return object             Response body
     */
    public function getKeywords(array $fields = array(), $limit = 10, $offset = 0)
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
            'offset'    => $offset,
        );

        return $this->request('keywords', $query);
    }

    /**
     * Get keyword information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getKeywordById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('keywords', $query, $id);
    }

    /**
     * Get people information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getPeople(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('people', $query);
    }

    /**
     * Get people information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getPeopleById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('people', $query, $id);
    }

    /**
     * Get platforms information.
     * 
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @param  integer    $offset Offset
     * @return object             Response body
     */
    public function getPlatforms(array $fields = array(), $limit = 10, $offset = 0)
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
            'offset'    => $offset,
        );

        return $this->request('platforms', $query);
    }

    /**
     * Get platform information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getPlatformById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('platforms', $query, $id);
    }

    /**
     * Get player perspectives information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getPlayerPerspectives(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('player_perspectives', $query);
    }

    /**
     * Get player perspectives information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getPlayerPerspectiveById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('player_perspectives', $query, $id);
    }

    /**
     * Get pulses information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getPulses(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('pulses', $query);
    }

    /**
     * Get pulse information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getPulseById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('pulses', $query, $id);
    }

    /**
     * Get release dates information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getReleaseDates(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('release_dates', $query);
    }

    /**
     * Get release date information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getReleaseDateById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('release_dates', $query, $id);
    }

    /**
     * Get series (Collection) information.
     * 
     * @param  array|null $fields Fields
     * @return object             Response body
     */
    public function getSeries(array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('collections', $query);
    }

    /**
     * Get serie information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getSerieById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('collections', $query, $id);
    }

    /**
     * Get themes information.
     * 
     * @param  array|null $fields Fields
     * @param  integer    $limit  Limit
     * @param  integer    $offset Offset
     * @return object             Response body
     */
    public function getThemes(array $fields = array(), $limit = 10)
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
            'limit'     => $limit,
        );

        return $this->request('themes', $query);
    }

    /**
     * Get theme information by id.
     * 
     * @param  integer $id    Genre ID
     * @param  array  $fields Fields
     * @return object         Response body
     */
    public function getThemeById($id, array $fields = array())
    {
        $query = array(
            'fields'    => $this->fieldsBuilder($fields),
        );

        return $this->request('themes', $query, $id);
    }
}