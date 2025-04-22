<?php

namespace App\Models; 

class City {
    private $id;
    private $name;
    private $country;
    private Coord $coord;   

    public function __construct($id, $name, $country, Coord $coord) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->coord = $coord;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getCoord() {
        return $this->coord;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function setCoord(Coord $coord) {
        $this->coord = $coord;
    }
}