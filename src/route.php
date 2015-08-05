<?php

Route::get('setting-test', function () {
    return \Unisharp\Setting\Setting::test();
});