<?php

namespace App\Controller\Admin;
use App\Entity\Room;
use App\Entity\ImagesRoom;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;

class ImagesRoomCrudController extends AbstractCrudController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }
    public static function getEntityFqcn(): string
    {
        return ImagesRoom::class;
    }

    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Images room')
            ->setEntityLabelInSingular('Image room')
            ->setPageTitle('index', "Gestion d'images des salles")
            ;
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Si l'entité est une instance de ImagesRoom et qu'elle n'a pas encore de salle associée
        if ($entityInstance instanceof ImagesRoom && !$entityInstance->getRoom()) {
            // Récupérez l'ID de la salle à partir des paramètres de requête
            $roomId = $this->request->query->get('roomId');

            if ($roomId) {
                // Récupérez la salle correspondante depuis la base de données
                $room = $entityManager->getRepository(Room::class)->find($roomId);

                if ($room) {
                    // Associez la salle à l'entité ImagesRoom
                    $entityInstance->setRoom($room);
                }
            }
        }

        // Poursuivez la persistance de l'entité
        parent::persistEntity($entityManager, $entityInstance);
    }

   

  


    public function configureFields(string $pageName): iterable
{
    $request = $this->requestStack->getCurrentRequest();
    $roomId = $request->query->get('roomId');
    
    if ($roomId) {
        $room = $this->entityManager->getRepository(Room::class)->find($roomId);
    }
    
    $fields = [
        FormField::addPanel('Salle')
            ->setIcon('fa-brands fa-codepen')
            ->setHelp('Salle'),
        
        // Ici, vous configurez le champ de l'association avec l'entité Room récupérée (si elle existe)
        AssociationField::new('room', 'Salle')
            ->setValue($room)
            ->setFormTypeOptions(['disabled' => (bool) $room]), // Disable the field if room is set

        FormField::addPanel('Chemin de l\'image')
            ->setIcon('fa-solid fa-image')
            ->setHelp('Saisissez le chemin d\'accès pour l\'image'),
            ImageField::new('path', 'Image')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads/')
            ->setUploadedFileNamePattern(
            fn (UploadedFile $file): string => sprintf('%s-%s', time(), $file->getClientOriginalName())
        )
        ->setRequired(true),
    ];
    
    return $fields;
}

}
