
# Laravel NYTimes API Package

A lightweight Laravel package to easily integrate the New York Times API into your Laravel applications.
Fetch the latest news, articles, and top stories directly from NYTimes with simple and elegant syntax.




## 🚀 Features

- Simple integration with the New York Times API
- Supports Top Stories, Most Popular, and Article Search endpoints
- Built-in Facade (NYTimes) and helper methods
- Powered by GuzzleHTTP for secure API requests
- Compatible with Laravel 9, 10, 11 and 12


## 📦 Installation

```bash
  composer require shamithewebdeveloper/laravel-nytimes
```

## 🔧 Configuration

1. Publish the config file:

```bash
  php artisan vendor:publish --tag=nytimes-config
```

2. Add your NYTimes API Key(Public Key) in .env:

`NYTIMES_API_KEY=your_api_key_here`






## 📝 Usage 

Retrieve Data From API
```php
NYTimes::searchArticle('Sports', now()->lastWeekDay, now())->get();
// use get() to return response in array

NYTimes::searchArticle('Sports', now()->lastWeekDay, now())->json();
// use json() to return response in array
```

Get a full response from GuzzleHttp
```php
$articlesFullResponse=NYTimes::searchArticle('Sports', '2024-04-01', now())->fullResponse();
dd($articlesFullResponse);
```
Get API Request URL
```php
$articleURL=NYTimes::searchArticle('Sports', '2024-04-01', now())->requestURL();
echo $articleURL;
```

### Examples

For Article Search:
```php
/*
Parameters
$query (string required), $start_date (string), $end_date (string), $filter_query (string), $sort (string), $page=1 (int)
*/

$articles=NYTimes::searchArticle('Sports', now()->lastWeekDay, now())->get();

foreach ($articles['response']['docs'] as $article)
{
  echo $article['abstract'].'<br>';
  echo $article['web_url'].'<br>';
}
```


## 📜 License

This package is open-sourced software licensed under the MIT license.

