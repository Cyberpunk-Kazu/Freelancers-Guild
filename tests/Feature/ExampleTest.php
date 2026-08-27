<?php

test('the application redirects visitors to sign in', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
