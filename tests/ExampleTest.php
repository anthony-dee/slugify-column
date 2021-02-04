<?php

namespace AnthonyDee\SlugifyColumn\Tests;

use Orchestra\Testbench\TestCase;
use AnthonyDee\SlugifyColumn\SlugifyColumnServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [SlugifyColumnServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
