<?php

namespace evan_klein\sitemap;

require_once('ek.php');
use evan_klein\ek as ek;

class Sitemap {
	private $base_url = NULL;
	private $urls = [];


	public function __construct($base_url=NULL, $urls=NULL){
		// $this->base_url
		if( \is_null($base_url) ){
			$protocol = ek\isHTTPS() ? 'https://':'http://';
			$domain_name = ek\getDomainName();
			$this->base_url = $protocol . $domain_name;
		}

		// $this->urls
		if( \is_array($urls) ) $this->urls = $urls;

		return $this;
	}


	public function addURL(string $loc, $lastmod=NULL, $changefreq=NULL, $priority=NULL){
		$this->urls[]=[
			'loc' => $loc,
			'lastmod' => $lastmod,
			'changefreq' => $changefreq,
			'priority' => $priority
		];

		return $this;
	}


	public function output(): string {
		$output = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

XML;

		foreach($this->urls as $url){
			$loc_xs = ek\xmlSafe(
				$this->base_url . $url['loc']
			);

			$output.="\t<url>";
				$output.="\n\t\t<loc>$loc_xs</loc>";

				// lastmod
				if( isset($url['lastmod']) ){
					ek\throwIfNotW3CDatetime($url['lastmod'], 'lastmod');
					$lastmod_xs = ek\xmlSafe($url['lastmod']);
					$output.="\n\t\t<lastmod>$lastmod_xs</lastmod>";
				}

				// changefreq
				if( isset($url['changefreq']) ){
					ek\throwIfNotInArray(
						$url['changefreq'],
						[
							'always',
							'hourly',
							'daily',
							'weekly',
							'monthly',
							'yearly',
							'never'
						],
						'changefreq'
					);
					$changefreq_xs = ek\xmlSafe($url['changefreq']);
					$output.="\n\t\t<changefreq>$changefreq_xs</changefreq>";
				}

				// priority
				if( isset($url['priority']) ){
					// TODO - use ek\ function for validation
					if(
						!(
							\is_float($url['priority'])
							&&
							$url['priority']>=0.0
							&&
							$url['priority']<=1.0
						)
					) throw new \Exception("Invalid value for 'priority': " . $url['priority'], 400);
					$priority_xs = ek\xmlSafe($url['priority']);
					$output.="\n\t\t<priority>$priority_xs</priority>";
				}

			$output.="\n\t</url>\n";
		}

		$output.='</urlset>';

		return $output;
	}
}

?>