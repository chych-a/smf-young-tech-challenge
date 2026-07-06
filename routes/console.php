<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('challenge:ping', function () {
    $this->info('SMF Young Tech Challenge API is installed.');
});
