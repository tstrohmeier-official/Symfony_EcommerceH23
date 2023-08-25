<?php

namespace App\Entity;

class Cart
{

    private $items = [];

    public function add($product)
    {
        $index = $product->getIdProduct();

        if (array_key_exists($index, $this->items)) {
            $purchase = $this->items[$product->getIdProduct()];

            $newQuantity = $purchase->getQuantity();
            $newQuantity++;

            $purchase->setQuantity($newQuantity);
            $this->items[$index] = $purchase;
        } else {
            $purchase = new Purchase($product);
            $this->items[$product->getIdProduct()] = $purchase;
        }
    }

    public function update($modifications)
    {
        if (count($this->items) > 0) {
            $newQuantities = $modifications["txtQuantity"];

            foreach ($this->items as $key => $item) {
                $newQuantity = $newQuantities[$key];
                
                if ($newQuantity >= 0 && $newQuantity <= 99 && is_numeric($newQuantity)) {
                    if ($newQuantity == 0) {
                        $this->delete($key);
                    } else {
                        $item->setQuantity($newQuantity);
                    }
                }
            }
        }
    }


    public function delete($index)
    {
        if (array_key_exists($index, $this->items)) {
            unset($this->items[$index]);
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getSubtotal(){
        return $this->calculatePricing();
    }

    public function getTps() {
        return $this->calculatePricing(Constants::TPS);
    }

    public function getTvq() {
        return $this->calculatePricing(Constants::TVQ);
    }

    public function getDeliveryFee(){
        return count($this->items) > 0 ? Constants::DELIVERY_FEE : 0;
    }

    public function getTotal() {

        $total = $this->getSubtotal();
        $total += $this->getTps();
        $total += $this->getTvq();
        $total += $this->getDeliveryFee();

        return $total;
    }

    public function getStripePriceInCents() {

        // get price without taxes
        $total = $this->getTotal() * 100;
        return round($total, 0, PHP_ROUND_HALF_UP);
    }

    private function calculatePricing($target = null){
        $total = 0;

        if (count($this->items) > 0){
            foreach ($this->items as $item) {
                $total += ($item->getPrice() * $item->getQuantity());
            }
        }

        return $target != null ? $total * $target : $total;
    }

    public function isEmpty() {
        if (count($this->items) === 0) {
            return true;
        }
        return false;
    }
}
