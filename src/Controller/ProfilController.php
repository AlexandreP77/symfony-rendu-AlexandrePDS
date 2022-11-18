<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\UserType;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        $id = $this->getUser();
        return $this->render('profil/profil.html.twig', [
            'articles' => $articleRepository-> findArticleUser($id),
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/editprofil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/profil/delete', name: 'app_profil_delete', methods: ['POST'])]
    public function delete(Request $request, UserRepository $userRepository): Response
    {   
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
    }
}
