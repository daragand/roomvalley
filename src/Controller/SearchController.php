<?php

namespace App\Controller;


use App\Service\SearchService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET','POST'])]
    public function searchRoom(SearchService $search,RequestStack $request,PaginatorInterface $paginator,): Response
    {

        //lancement de la recherche dans le service Search
         
        $searchReq = $search->search();

        

        
//         $firstEquipment = true;
// if (!$ergonomies){
// foreach ($ergonomies as $ergoId) {
//     $joinType = $firstEquipment ? 'join' : 'orWhere';
//     $rooms->$joinType('room.ergonomy', 'ergonomy' . $ergoId)
//         ->andWhere('ergonomy' . $ergoId . '.id = :ergonomyId' . $ergoId)
//         ->setParameter('ergonomyId' . $ergoId, $ergoId);

//     $firstEquipment = false;
// }
//}

        //comptage des résultats. Permet de savoir si il y a des résultats ou non et de les afficher dans la vue.
        $nbResults = count($searchReq);
        

        /**
         * pagination des résultats à l'aide de Paginator. Chaque page contient 12 résultats.
         * ce critère peut être modifié en modifiant le chiffre 12.
         * La pagination débute à la page 1. La méthode consiste à récupérer les résultats de recherche et de les répartir en fonction du total en nombre de page.
         */
        // 
        $results = $paginator->paginate(
            $searchReq,
            $request->getCurrentRequest()->query->getInt('page', 1),
            12
         );
         // récupération des critères de recherche complété par l'usager et on les affiche dans la vue.
         $req = $request->getCurrentRequest()->query->get('q');
            $equmt = $request->getCurrentRequest()->query->get('equipement');

            // dd($equmt);

        return $this->render('search/results.html.twig', [
            'name_page' => 'Résultats de recherche',
            'results' => $results,
            'query' => $req,
            'nbResults' => $nbResults,
            

        ]);
    }
}
