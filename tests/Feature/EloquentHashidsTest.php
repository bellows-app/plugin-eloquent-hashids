<?php

use Bellows\Plugins\EloquentHashids;

it('can set the env variable', function ($method) {
    $result = $this->plugin(EloquentHashids::class)->{$method}();

    expect($result->getEnvironmentVariables()['HASH_IDS_SALT'])->toBeString();
})->with(['deploy', 'install']);
