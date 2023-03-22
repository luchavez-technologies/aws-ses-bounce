<?php

it('has root page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
