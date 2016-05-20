<?php

namespace AppBundle\Twig;

class DottedSliceExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("DottedSlice", array($this, "dottedSliceFilter")),
        );
    }

    public function dottedSliceFilter($value, $max)
    {
        if (strlen($value) > $max)
        {
            return substr($value, 0, $max)."...";
        }
        else {
            return $value;
        }
        return $value == 1 ? 1 : "";
    }
    
    public function getName()
    {
        return "dotted_slice_extension";
    }
}
