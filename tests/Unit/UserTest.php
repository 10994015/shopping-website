<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
  
    public function test_example(){
        $response = $this->post('/api/products', [
            'title'=>'product name',
            'image'=>'',
            'price'=>200,
        ]);

        $response->assertStatus(302);

        $response = $this->followRedirects($response);
    
        $response->assertStatus(200);
        $response->assertSee(200);

    }
}
