<?php 
namespace App\Validator\Constraints;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RoomNotReservedValidator extends ConstraintValidator {
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint) {
        // $value représente la valeur sur laquelle la contrainte est appliquée. 
        // Dans ce cas, il s'agit de la date de début. Cependant, pour effectuer notre validation,
        // nous avons besoin de l'objet Reservation complet et non uniquement de la date de début.
        $reservationData = $this->context->getRoot()->getData();
        
        // Si $reservationData n'est pas une instance de Reservation, nous arrêtons la validation ici.
        if (!$reservationData instanceof Reservation) {
            return;
        }
        
        // Récupération des données nécessaires pour la vérification
        $room = $reservationData->getRoom();
        $dateStart = $reservationData->getDateStart();
        $dateEnd = $reservationData->getDateEnd();
        
        // Appel à la méthode findOverlappingReservations du dépôt Reservation
        // Cette méthode devrait retourner les réservations existantes qui se chevauchent 
        // avec la période définie par $dateStart et $dateEnd pour une salle spécifique ($room).
        $overlappingReservations = $this->em->getRepository(Reservation::class)->findOverlappingReservations($room, $dateStart, $dateEnd);
        
        // Si nous trouvons des réservations qui se chevauchent, 
        // nous ajoutons une violation à l'aide du message défini dans la contrainte.
        // Cette violation sera liée au champ 'date_start' de notre formulaire.
        if ($overlappingReservations) {
            $this->context->buildViolation($constraint->message)->atPath('date_start')->addViolation();
        }
    }
}