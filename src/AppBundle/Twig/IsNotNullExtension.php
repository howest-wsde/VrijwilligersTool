<?php

namespace AppBundle\Twig;

class IsNotNullExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("isNotNull", array($this, "isNotNullFilter")),
        );
    }

    public function isNotNullFilter($value)
    {
        return $value == 1 ? 1 : "";
    }

    public function getName()
    {
        return "is_not_null_extension";
    }
}
