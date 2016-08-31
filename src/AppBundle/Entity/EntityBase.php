<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EntityBase
{
    /**
     * Get the class name
     *
     * @return string
     */
    public function getClassName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }

    /**
     * returns if the class type is that of the given value
     *
     * @return bool
     */
    public function isOfType($type)
    {
        return $this->getClassName() == $type;
    }
}
