
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

```php
/*
  Parameters
  $query (string required)
  $start_date (string)
  $end_date (string)
  $filter_query (string)
  $sort (string)
  $page=1 (int)
*/

$articles=NYTimes::searchArticle(
    'Sports',
    now()->lastWeekDay,
    now()
    )->get();
// returns an array

foreach ($articles['response']['docs'] as $article)
{
  echo $article['abstract'].'<br>';
  echo $article['web_url'].'<br>';
}
```

