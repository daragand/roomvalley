<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
// Cette classe définit une contrainte personnalisée nommée "RoomNotReserved".
class RoomNotReserved extends Constraint {
    // Cette propriété contient le message d'erreur qui sera affiché 
    // si la contrainte n'est pas respectée (la salle est déjà réservée pour la date spécifiée).
    public $message = 'La salle est déjà réservée pour cette date.';
}
