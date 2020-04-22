<?php

namespace Catalogue;

interface ProductRepository
{
    public function save(Product $product): void;

    public function get(string $id): Product;
}