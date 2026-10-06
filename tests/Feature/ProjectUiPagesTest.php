<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProjectUiPagesTest extends TestCase
{
    public function test_public_and_admin_pages_render(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/events')->assertOk();
        $this->get('/booking')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/admin')->assertOk();
        $this->get('/admin/bookings')->assertOk();
    }
}
