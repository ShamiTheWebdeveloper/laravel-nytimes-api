
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

Retrive Data From API
```php
NYTimes::searchArticle('Sports', now()->lastWeekDay, now())->get();
// use get() to get response in array

NYTimes::searchArticle('Sports', now()->lastWeekDay, now())->json();
// use json() to get response in json
```

Get full response from GuzzleHttp
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
For Archive:
```php
/*
Parameters
$month (int required), $year (int required)
*/

$archives= NYTimes::archive(5,2025)->get();

foreach ($archives['response']['docs'] as $archive) {
    echo $archive['abstract'].'<br>';
    echo $archive['section_name'].'<br>';
    echo $archive['word_count'].'<br>';
}

```
For Most Popular:
```php
/*
Parameters
$type (string required), $period (int), $shareType (string)
*/

$populars= NYTimes::mostPopular('emailed',7)->get();

foreach ($populars['results'] as $popular) {
    echo $popular['section'].'<br>';
    echo $popular['title'].'<br>';
    echo $popular['abstract'].'<br>';
}
```
For Books (Overview)
```php
/*
Parameters
$publishedDate (string)
*/

$books=NYTimes::bookOverview('2024-05-04')->get();

echo $books['results']['published_date'].'<br>';
echo '<h3>Books:</h3>';

foreach ($books['results']['lists'] as $booklist)
{
  echo $booklist['list_name'].'<br>';

  foreach ($booklist['books'] as $book)
  {
    echo $book['title'].'<br>';
    echo $book['author'].'<br>';
  }
}
```
For Books (List)
```php
/*
Parameters
$list (string required) ,$date (string)
*/

$books=NYTimes::bookList('series-books')->get();

echo '<h2>'.$books['results']['list_name'].'</h2>';

foreach ($books['results']['books'] as $book)
{
    echo $book['title'].'<br>';
    echo $book['author'].'<br>';
}

```

## 📜 License

This package is open-sourced software licensed under the MIT license.

