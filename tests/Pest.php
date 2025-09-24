<?php

uses(
    Illuminate\Foundation\Testing\RefreshDatabase::class,
    Illuminate\Foundation\Testing\WithFaker::class,
)->in('Feature');

require __DIR__.'/CreatesUsers.php';
