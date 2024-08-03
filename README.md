# Sitemap

A simple PHP library for generating [sitemaps](https://www.sitemaps.org/).

## Requirements

- [ek.php](https://github.com/evan-klein/ek/blob/master/ek.php)

## Example

```php
<?php

require_once('/usr/local/lib/evan-klein/ek/ek.php');
use evan_klein\ek as ek;

ek\sendXMLHeader();

require_once('/usr/local/lib/evan-klein/sitemap/Sitemap.php');
$sitemap = new \evan_klein\sitemap\Sitemap('https://www.example.com');

$sitemap->addURL('/')
	->addURL('/blog/', '2005-01-01', 'monthly', 0.8);

echo $sitemap->output();

?>
```

The code above generates the sitemap below:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>https://www.example.com/</loc>
	</url>
	<url>
		<loc>https://www.example.com/blog/</loc>
		<lastmod>2005-01-01</lastmod>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
	</url>
</urlset>
```