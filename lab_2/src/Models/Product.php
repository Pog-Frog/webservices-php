<?php

namespace App\Models;

class Product {
    private $name;
    private $price;
    private $units_in_stock;

    public function __construct($name, $price, $units_in_stock) {
        $this->name = $name;
        $this->price = $price;
        $this->units_in_stock = $units_in_stock;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function get_units_ins_stock() {
        return $this->units_in_stock;
    }
}