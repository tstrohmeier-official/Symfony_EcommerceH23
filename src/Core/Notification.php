<?php

namespace App\Core;

class Notification
{

    private $tag;
    private $color;
    private $content;
    private $icon;

    public function __construct($tag, $content, $color = NotificationColor::PRIMARY, $icon = Icons::THUMB_UP)
    {
        $this->tag = $tag;
        $this->color = $color;
        $this->content = $content;
        $this->icon = $icon;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getContent()
    {
        return $this->content;
    }

    public static function createOutOfStockNotification($idOrder, $items){
        $content = "";

        foreach ($items as $item) {
            $content = $content . "Product " . $item->getProductName() . " is out of stock<br>";
        }

        $content = $content . "<br>The order #" . $idOrder . " will be delivered as soon as missing products are availables.";

        return new Notification("Success", $content, NotificationColor::SUCCESS);
    }

    public function getIcon() {
        return $this->icon;
    }
}

abstract class NotificationColor
{
    public const PRIMARY = "alert-primary";
    public const SECONDARY = "alert-secondary";
    public const SUCCESS = "alert-success";
    public const DANGER = "alert-danger";
    public const WARNING = "alert-warning";
    public const INFO = "alert-info";
    public const LIGHT = "alert-light";
    public const DARK = "alert-dark";
}

abstract class Icons {
    public const THUMB_UP = "fa-solid fa-thumbs-up";
    public const WARNING = "fa-solid fa-triangle-exclamation";
}
