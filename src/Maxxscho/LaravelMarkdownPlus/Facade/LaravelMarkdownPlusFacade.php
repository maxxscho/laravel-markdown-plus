<?php namespace Maxxscho\LaravelMarkdownPlus\Facade;

use Illuminate\Support\Facades\Facade;

class LaravelMarkdownPlusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'markdownplus';
    }
}