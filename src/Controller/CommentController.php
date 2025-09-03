<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Wish;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Expression;

final class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    #[Route('/wishes/{id}/comments/create', name: 'comment_create', methods:
    ['GET','POST'])]
    #[IsGranted("ROLE_USER")]
    public function create(?Wish $wish, Request $request, EntityManagerInterface
                                 $em): Response
    {
        if (!$wish){
            throw $this->createNotFoundException('This wish do not exists!');
        }
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setWish($wish);
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Comment successfully added!');
            return $this->redirectToRoute('wish_detail', [
                'id' => $wish->getId()
            ]);
        }
        return $this->render('comment/create.html.twig', [
            'commentForm' => $commentForm
        ]);
    }

    #[Route('/wishes/comments/{id}', name: 'comment_update', methods: ['GET','POST'])]
    public function update(?Comment $comment, Request $request, EntityManagerInterface
                                    $em): Response
    {
        if (!$comment){
            throw $this->createNotFoundException('This comment do not exists! Sorry!');
        }
        if (!$this->isGranted('ROLE_USER', null)) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à créer un cours');
        }
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setDateUpdated(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'Comment successfully updated!');
            return $this->redirectToRoute('wish_detail', [
                'id' => $comment->getWish()->getId()
            ]);
        }
        return $this->render('comment/create.html.twig', [
            'commentForm' => $commentForm
        ]);
    }

    #[Route('/wishes/comments/{id}/delete', name: 'comment_delete', requirements:
    ['id'=>'\d+'], methods: ['GET'])]
    public function delete(?Comment $comment, EntityManagerInterface $em, Request
                                    $request): Response
    {
        if (!$comment){
            throw $this->createNotFoundException('This comment does not exists!');
        }
        if (!$this->isGranted('ROLE_USER', null)) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à créer un cours');
        }
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->query->get
        ('token'),)) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'This comment has been deleted');
        }
        else {
            $this->addFlash('danger', 'This wish cannot be deleted');
        }
        return $this->redirectToRoute('wish_detail', [
            'id' => $comment->getWish()->getId()
        ]);
    }
}
