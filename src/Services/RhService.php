<?php


namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RhService {
    
    private $urlApi;
    private $client;
    public function __construct(HttpClientInterface $client,  $urlApi)
    {
        $this->client = $client;
        $this->urlApi = $urlApi;
    }

    public function getPeople(){
        return json_decode(
            $this->client
                ->request('GET', $this->urlApi . '?method=people')
                ->getContent(),
            true
        );
    }

    public function getDayTeam( $date )
    {
    } 
}



?>