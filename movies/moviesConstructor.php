<?php

abstract class MoviesConstructor
{
    protected string $api_key = "3ac6f33a";

    public $title;
    public $year;
    abstract public function movieLookUp($title,$year);
}