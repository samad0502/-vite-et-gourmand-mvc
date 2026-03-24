<?php
class Review {
private $db;
public function __construct($database)
{
    $this->db = $database;
}
}