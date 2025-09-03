<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    #[Route('/wish', name: 'app_wish')]
    public function index(): Response
    {
        return $this->render('wish/index.html.twig', [
            'controller_name' => 'WishController',
        ]);
    }

    #[Route('/wishes/{id}', name: 'wish_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
// récupère ce wish en fonction de l'id présent dans l'URL
        $wish = $wishRepository->find($id);
// s'il n'existe pas en bdd, on déclenche une erreur 404
        if (!$wish) {
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }
        return $this->render('wish/detail.html.twig', [
            "wish" => $wish
        ]);
    }

    #[Route('/wishes', name: 'wish_list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {
    // récupère les Wish publiés, du plus récent au plus ancien
    //$wishes = $wishRepository->findBy(['isPublished' => true], ['dateCreated' =>'DESC']);
    // appel d'une méthode personnalisée pour éviter d'avoir trop de requêtes.
    $wishes = $wishRepository->findPublishedWishesWithCategories();
    return $this->render('wish/list.html.twig', [
        "wishes" => $wishes
    ]);
}

    #[Route('/wishes/create', name: 'wish_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $wish->setUser($this->getUser());
// notre formulaire, associée à l'entité vide
        $wishForm = $this->createForm(WishType::class, $wish);
// récupère les données du form et les injecte dans notre $wish
        $wishForm->handleRequest($request);
// si le formulaire est soumis et valide...
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
// hydrate les propriétés absentes du formulaire
            $wish->setIsPublished(true);
// sauvegarde en bdd
            $em->persist($wish);
            $em->flush();
// affiche un message sur la prochaine page
            $this->addFlash('success', 'Idea successfully added!');
// redirige vers la page de détails de l'idée fraîchement créée
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }
// affiche le formulaire
        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }

    #[Route('/wishes/{id}/update', name: 'wish_update', requirements: ['id'=>'\d+'],
        methods: ['GET','POST'])]
    public function update(int $id, WishRepository $wishRepository, Request $request,
                           EntityManagerInterface $em): Response
    {
// récupère ce wish en fonction de l'id présent dans l'URL
        $wish = $wishRepository->find($id);
// s'il n'existe pas en bdd, on déclenche une erreur 404
        if (!$wish){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }
        if ($wish->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
// notre formulaire, associée à l'entité vide
        $wishForm = $this->createForm(WishType::class, $wish);
// récupère les données du form et les injecte dans notre $wish
        $wishForm->handleRequest($request);
// si le formulaire est soumis et valide...
        if ($wishForm->isSubmitted() && $wishForm->isValid()){
// hydrate les propriétés absentes du formulaire
            $wish->setDateUpdated(new \DateTimeImmutable());
// sauvegarde en bdd
            $em->flush();
// affiche un message sur la prochaine page
            $this->addFlash('success', 'Idea successfully updated!');
// redirige vers la page de détail de l'idée fraîchement modifiée
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }
// affiche le formulaire
        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }

    #[Route('/wishes/{id}/delete', name: 'wish_delete', requirements: ['id'=>'\d+'],
        methods: ['GET'])]
    public function delete(int $id, WishRepository $wishRepository, Request $request,
                           EntityManagerInterface $em): Response
    {$wish = $wishRepository->find($id);
        if (!$wish){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }
        if (!($wish->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete'.$id, $request->query->get('token'),)) {
            $em->remove($wish, true);
            $this->addFlash('success', 'This wish has been deleted');
        }
        else {
            $this->addFlash('danger', 'This wish cannot be deleted');
        }
// on retourne à la page de la liste
        return $this->redirectToRoute('wish_list');
    }
}
