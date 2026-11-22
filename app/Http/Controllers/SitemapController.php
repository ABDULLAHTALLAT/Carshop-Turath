<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = [
                [route('home'), now(), 'daily', '1.0'],
                [route('shop'), now(), 'hourly', '0.9'],
                [route('about'), null, 'monthly', '0.5'],
                [route('contact'), null, 'monthly', '0.5'],
            ];

            Car::visible()->select(['slug', 'updated_at'])->latest('updated_at')->limit(5000)->get()
                ->each(function (Car $car) use (&$urls) {
                    $urls[] = [route('shop.show', $car), $car->updated_at, 'weekly', '0.7'];
                });

            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($urls as [$loc, $date, $freq, $priority]) {
                $out .= '  <url><loc>'.e($loc).'</loc>'.($date ? '<lastmod>'.$date->toAtomString().'</lastmod>' : '')
                    .'<changefreq>'.$freq.'</changefreq><priority>'.$priority.'</priority></url>'."\n";
            }

            return $out.'</urlset>'."\n";
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
