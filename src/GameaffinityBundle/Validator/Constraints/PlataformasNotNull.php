<?php

namespace GameaffinityBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlataformasNotNull extends Constraint{
    
    public $message = "Debe seleccionarse al menos una plataforma.";
   
}
