<?php

use App\Console\Commands\ServeCommand;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        ServeCommand::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin'  => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'author' => \App\Http\Middleware\EnsureUserIsAuthor::class,
            'intern' => \App\Http\Middleware\EnsureUserIsIntern::class,
            'fellow' => \App\Http\Middleware\EnsureUserIsFellow::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin', 'admin/*')) {
                return route('login.show', 'admin');
            }
            if ($request->is('author', 'author/*')) {
                return route('login.show', 'guest');
            }
            if ($request->is('intern', 'intern/*')) {
                return route('login.show', 'intern');
            }
            if ($request->is('fellow', 'fellow/*')) {
                return route('login.show', 'fellow');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
