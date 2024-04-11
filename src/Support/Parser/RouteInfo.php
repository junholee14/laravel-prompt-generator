<?php

namespace Junholee14\LaravelPromptGenerator\Support\Parser;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;

class RouteInfo
{
    public function parse(string $method, string $uri): Route
    {
        $routes = $this->getRoutes();
        return $routes->filter(fn (Route $r) => Str::is($uri, $r->uri()) && Str::is($method, $r->methods()[0]))
            ->pipe(function (Collection $c) {
                if ($c->count() > 1) {
                    throw new \Exception('More than one route found');
                }

                return $c;
            })
            ->first();
    }

    private function getRoutes()
    {
        return collect(RouteFacade::getRoutes())
            ->filter(fn (Route $r) => $r->getAction('controller'))
            ->values();
    }
}
