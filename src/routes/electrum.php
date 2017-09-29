<?php

use Illuminate\Support\Facades\Route;

if (config('electrum.web_interface.enabled', false)) {
    Route::prefix(config('electrum.web_interface.prefix', 'electrum'))->group(function () {
        Route::get('/', 'AraneaDev\Electrum\App\IndexController');

        Route::prefix('api')->group(function () {
            Route::get('status', 'AraneaDev\Electrum\App\Api\StatusController');

            Route::get('history', 'AraneaDev\Electrum\App\Api\HistoryController@index');
            Route::get('history/{address}', 'AraneaDev\Electrum\App\Api\HistoryController@show')
                ->where('address', '^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$');
            Route::get('history/tx/{txid}', 'AraneaDev\Electrum\App\Api\HistoryController@details');

            Route::get('addresses', 'AraneaDev\Electrum\App\Api\AddressController@index');
            Route::get('addresses/unused', 'AraneaDev\Electrum\App\Api\AddressController@unused');
            Route::get('addresses/is_mine{address}', 'AraneaDev\Electrum\App\Api\AddressController@is_mine')
                ->where('address', '^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$');
            Route::get('addresses/{address}', 'AraneaDev\Electrum\App\Api\AddressController@check')
                ->where('address', '^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$');

            Route::prefix('requests')->group(function () {
                Route::get('/', 'AraneaDev\Electrum\App\Api\RequestsController@index');
                Route::get('{address}', 'AraneaDev\Electrum\App\Api\RequestsController@show')
                    ->where('address', '^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$');

                Route::post('/', 'AraneaDev\Electrum\App\Api\RequestsController@create');

                Route::delete('/', 'AraneaDev\Electrum\App\Api\RequestsController@clear');
                Route::delete('{address}', 'AraneaDev\Electrum\App\Api\RequestsController@destroy')
                    ->where('address', '^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$');
            });
        });
    });
}
