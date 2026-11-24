<?php

test('the application returns a successful response', function () {
    $response = $this->get('/id');

    $response->assertStatus(200);
});
