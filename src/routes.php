<?php

Route::get('setting-test', 'Unisharp\Setting\Controllers\ExampleController@index');

Route::get('setting-test/search', 'Unisharp\Setting\Controllers\ExampleController@search');

Route::get('setting-test/save', 'Unisharp\Setting\Controllers\ExampleController@save');

Route::get('setting-test/drop', 'Unisharp\Setting\Controllers\ExampleController@drop');
