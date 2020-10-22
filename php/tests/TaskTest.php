<?php
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase {
    /**
     * Iterate directory and check files readability
     */
    public function testSource() {
        $this->assertFileExists("../data.csv");
        if(!is_readable("../data.csv")) {
            throw new FileNotReadableException("Error Processing Request: the given file is not readable");
        }
    }
}
