<?php

namespace ShamiTheWebdeveloper\NYTimes;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class NYTimes
{
    private static string $baseURL = 'https://api.nytimes.com/svc';

    private static string $rssFeedBaseURL = 'https://api.nytimes.com/services/xml';

    private static array $articleSearchSorts = ['best', 'newest', 'oldest', 'relevance'];

    private static array $mostPopularPeriods = [1, 7, 30];

    private static array $mostPopularTypes = ['emailed','shared','viewed'];

    private static array $mostPopularShareTypes = ['facebook'];

    private static array $timesNewswireSources=['all', 'nyt', 'inyt'];

    private static array $topStoriesSections=['arts', 'automobiles', 'books/review', 'business', 'fashion', 'food', 'health', 'home', 'insider', 'magazine', 'movies', 'nyregion', 'obituaries', 'opinion', 'politics', 'realestate', 'science', 'sports', 'sundayreview', 'technology', 'theater', 't-magazine', 'travel', 'upshot', 'us', 'world'];

    private static array $rssFeedSections=["Africa", "Americas", "ArtandDesign", "Arts", "AsiaPacific", "Automobiles", "Baseball", "Books/Review", "Business", "Climate", "CollegeBasketball", "CollegeFootball", "Dance", "Dealbook", "DiningandWine", "Economy", "Education", "EnergyEnvironment", "Europe", "FashionandStyle", "Golf", "Health", "Hockey", "HomePage", "Jobs", "Lens", "MediaandAdvertising", "MiddleEast", "MostEmailed", "MostShared", "MostViewed", "Movies", "Music", "NYRegion", "Obituaries", "PersonalTech", "Politics", "ProBasketball", "ProFootball", "RealEstate", "Science", "SmallBusiness", "Soccer", "Space", "Sports", "SundayBookReview", "Sunday-Review", "Technology", "Television", "Tennis", "Theater", "TMagazine", "Travel", "Upshot", "US", "Weddings", "Well", "World", "YourMoney"];

    private static ?Response $response = null;

    /**
     * Search Articles
     *
     * @param string $query
     * @param string $start_date
     * @param string $end_date
     * @param string $filter_query
     * @param string $sort
     * @param int $page
     * @param string $version
     * @return NYTimes
     * @throws Exception
     */
    public static function searchArticle(string $query, string $start_date='', string $end_date='', string $filter_query='', string $sort='best', int $page = 0, string $version = 'v2'): NYTimes
    {
        $service   = 'search';
        $jsonCall  = 'articlesearch';
        $url       = self::makeURL($service, $version, $jsonCall);

        if (!in_array($sort, self::$articleSearchSorts))
            self::exception('Available sorts for Article search are '.implode(', ', self::$articleSearchSorts));

        $params    =[
            'q'       => $query,
            'page'    => $page,
            'sort'    => $sort,
        ];

        if (!empty($start_date))
            $params['begin_date'] = date("Ymd", strtotime($start_date));

        if (!empty($end_date))
            $params['end_date'] = date("Ymd", strtotime($end_date));

        if (!empty($filter_query))
            $params['fq'] = $filter_query;

        self::sendRequest($url, $params);

        return new self;
    }


    public static function archive(int $month, int $year, string $version = 'v1'): NYTimes
    {
        $service   = 'archive';
        $jsonCall  = $year.'/'.$month;
        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url);

        return new self;
    }

    public static function mostPopular(string $type, int $period=1, string $shareType='facebook', string $version = 'v2'): NYTimes
    {
        if (!in_array($period, self::$mostPopularPeriods))
            self::exception('Available periods in Most Popular are '.implode(', ', self::$mostPopularPeriods));

        if (!in_array($type, self::$mostPopularTypes))
            self::exception('Available types in Most Popular are '.implode(', ', self::$mostPopularTypes));

        $service   = 'mostpopular';
        $jsonCall  = $type.'/'.$period;

        if ($type==self::$mostPopularTypes[1]){
            if (!in_array($shareType, self::$mostPopularShareTypes))
                self::exception('Available Shared types in Most Popular are '.implode(', ', self::$mostPopularShareTypes));

            $jsonCall.='/'.$shareType;
        }

        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url);

        return new self;
    }

    public static function bookOverview(string $publishedDate='', string $version = 'v3'): NYTimes
    {
        $service   = 'books';
        $jsonCall  = 'lists/overview';
        $params    = [];

        if (!empty($publishedDate))
            $params['published_date'] = date("Y-m-d", strtotime($publishedDate));

        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url, $params);

        return new self;
    }

    public static function bookList(string $list, string $date='', string $version = 'v3'): NYTimes
    {
        $d='current';
        if (!empty($date))
            $d=date("Y-m-d", strtotime($date));

        $service   = 'books';
        $jsonCall  = 'lists/'.$d.'/'.$list;

        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url);

        return new self;

    }

    /**
     * @throws Exception
     */
    public static function timesNewswire(string $source, string $section='all', int $limit=20, int $offset=0, string $version = 'v3'): NYTimes
    {
        if (!in_array($source, self::$timesNewswireSources))
            self::exception('Available source types in Times Newswire '.implode(', ', self::$timesNewswireSources));

        $service          = 'news';
        $jsonCall         = 'content/'.$source.'/'.$section;
        $params['limit']  = $limit;
        $params['offset'] = $offset;

        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url, $params);

        return new self;
    }

    public static function newsSectionList(string $version = 'v3'): NYTimes
    {
        $service   = 'news';
        $jsonCall  = 'content/section-list';

        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url);

        return new self;
    }

    public static function timesNewswireUrl(string $url='https%3A%2F%2Fwww.nytimes.com%2F2018%2F09%2F19%2Fworld%2Fasia%2Fnorth-south-korea-nuclear-weapons.html', string $version = 'v3'): NYTimes
    {
        $service   = 'news';
        $jsonCall  = 'content';
        $params['url'] = $url;

        $path       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($path, $params);

        return new self;
    }

    /**
     * @throws Exception
     */
    public static function topStories(string $section='home', string $version = 'v2'): NYTimes
    {
        if (!in_array($section, self::$topStoriesSections))
            self::exception('Available sections in Top Stories are '.implode(', ', self::$topStoriesSections));

        $service   = 'topstories';
        $jsonCall  = $section;

        $url       = self::makeURL($service, $version, $jsonCall);

        self::sendRequest($url);

        return new self;
    }

    public static function rssFeed($section='HomePage'): NYTimes
    {
        if (!in_array($section,self::$rssFeedSections))
            self::exception('Available sections in RSS Feed are '.implode(', ', self::$rssFeedSections));

        $service   = 'rss';
        $jsonCall  = 'nyt/'.$section;
        self::$baseURL= self::$rssFeedBaseURL;

        $url       = self::$baseURL."/{$service}/{$jsonCall}.xml";

        self::$response= Http::get($url);

        return new self;
    }

    /**
     * @throws Exception
     */
    public static function get()
    {
        self::checkResponse();
        if (self::$baseURL==self::$rssFeedBaseURL) {
            $xml = simplexml_load_string(self::$response->body());
            return json_decode(json_encode($xml), true);
        }
        self::checkResponse();
        return self::$response->json();
    }

    /**
     * @throws Exception
     */
    public static function json()
    {
        self::checkResponse();
        if (self::$baseURL==self::$rssFeedBaseURL) {
            $xml = simplexml_load_string(self::$response->body());
            return json_encode($xml);
        }
        return json_encode(self::$response->json());
    }

    /**
     * @throws Exception
     */
    public static function toXML()
    {
        if (self::$baseURL!=self::$rssFeedBaseURL)
            self::exception('toXML() function only valid for RSS API');

        self::checkResponse();
        return self::$response->body();
    }

    /**
     * @throws Exception
     */
    public static function redirectToXML(){
        if (self::$baseURL!=self::$rssFeedBaseURL)
            self::exception('RedirectToXML() function only valid for RSS API');

        self::checkResponse();
        return redirect()->to(self::requestUrl());
    }

    public static function fullResponse()
    {
        return self::$response;
    }

    public static function requestUrl(): string
    {
        return self::$response->effectiveUri()->__toString();
    }

    private static function sendRequest($url, array $params=[])
    {
        $params['api-key'] = config('services.nytimes.api_key', '');
        if (empty($params['api-key']))
            self::exception('You must set NYTimes API key');
        self::$response= Http::get($url, $params);
    }

    /**
     * @throws Exception
     */
    private static function checkResponse()
    {
        if (self::$response->failed()){
            return ['error' => [
                'status' => self::$response->status(),
                'message' => json_decode(self::$response->body()),
                'reason' => self::$response->reason(),
                'request_url' => self::requestUrl(),
            ]];
        }
    }

    /**
     * Build the full API URL
     */
    private static function makeURL(string $service, string $version, string $jsonCall): string
    {
        return self::$baseURL . "/{$service}/{$version}/{$jsonCall}.json";
    }

    /**
     * @throws Exception
     */
    private static function exception($message)
    {
        throw new Exception($message);
    }
}
