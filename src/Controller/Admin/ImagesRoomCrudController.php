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

    // Définit l'entité gérée par ce contrôleur
    public static function getEntityFqcn(): string
    {
        return ImagesRoom::class;
    }

    // Configure le comportement global du CRUD pour cette entité
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Images room')
            ->setEntityLabelInSingular('Image room')
            ->setPageTitle('index', "Gestion d'images des salles");
    }

    // Personnalise le processus de persistance de l'entité
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Si l'entité est une instance de ImagesRoom et qu'elle n'a pas encore de salle associée
        if ($entityInstance instanceof ImagesRoom && !$entityInstance->getRoom()) {
            // Récupère l'ID de la salle à partir des paramètres de requête
            $roomId = $this->request->query->get('roomId');

            if ($roomId) {
                // Récupère la salle correspondante depuis la base de données
                $room = $entityManager->getRepository(Room::class)->find($roomId);

                if ($room) {
                    // Associe la salle à l'entité ImagesRoom
                    $entityInstance->setRoom($room);
                }
            }
        }

        // Poursuit la persistance de l'entité
        parent::persistEntity($entityManager, $entityInstance);
    }

    // Configure les champs du formulaire d'édition et de création
    public function configureFields(string $pageName): iterable
    {
        // Récupère l'ID de la salle depuis les paramètres de requête (ou utilise l'ID de la dernière salle créée s'il n'est pas présent)
        $request = $this->requestStack->getCurrentRequest();
        $roomId = $request->query->get('roomId');

        if ($roomId) {
            $room = $this->entityManager->getRepository(Room::class)->find($roomId);
        } else {
            $room = $this->entityManager->getRepository(Room::class)->findOneBy([], ['id' => 'DESC']);
        }

        $fields = [
            // Champ "Salle" configuré avec PanelField et icône
            FormField::addPanel('Salle')
                ->setIcon('fa-brands fa-codepen')
                ->setHelp('Salle'),

            // Champ d'association "Salle" configuré pour afficher la salle sélectionnée ou la dernière salle créée
            AssociationField::new('room', 'Salle')
                ->setValue($room),

            // Champ "Chemin de l'image" configuré de la même manière que le champ "Salle" mais avec une icône différente
            FormField::addPanel('Chemin de l\'image')
                ->setIcon('fa-solid fa-image')
                ->setHelp('Saisissez le chemin d\'accès pour l\'image'),

            // Champ "Image" de type ImageField pour gérer les téléchargements d'images
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