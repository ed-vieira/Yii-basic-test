<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{

    public function assertIsNotNull($value) {
        $this->assertNotNull($value);
    }

    public function assertIsNull($value) {
        $this->assertNull($value);
    }

}
