<?php

namespace App\Entity;

abstract class Constants
{
    public const DELIVERY_FEE = 15;
    public const TPS = 0.05;
    public const TVQ = 0.09975;
    public const STATE_PREPARING = "In preparation";
    public const STATE_SENT = "Sent";
    public const STATE_TRANSIT = "In transit";
    public const STATE_DELIVERED = "Delivered";
    public const STATE_TO_COME = "To come";
    public const IMAGE_DEFAULT = '/images/products/imageMissing.jpg';
    public const PRODUCTS_IMAGES_PATH = '/images/products/';
}
