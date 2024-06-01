<?php

namespace App\Controller;

use App\Entity\Container;
use App\Form\ContainerType;
use App\service\TokenManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/container', name: 'app_container')]
class ContainerController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/', name: '_list')]
    public function list(): Response
    {
        $containers = $this->em->getRepository(Container::class)->findBy(['user' => $this->getUser()]);

        return $this->render('container/list.html.twig', [
            'containers' => $containers,
        ]);
    }

    #[Route('/new', name: '_new')]
    public function new(Request $request): Response
    {
        $container = new Container();

        $form = $this->createForm(ContainerType::class, $container);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $container->setUser($this->getUser());
            $container->setToken(TokenManager::generateToken());

            $this->em->persist($container);
            $this->em->flush();

            return $this->redirectToRoute('app_container_list');
        }

        return $this->render('container/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/refresh-token/{id}', name: '_refresh-token')]
    public function refreshToken(Container $container): Response
    {
        $container->setToken(TokenManager::generateToken());

        $this->em->flush();

        return $this->redirectToRoute('app_container_list');
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Container $container): Response
    {
        $this->em->remove($container);
        $this->em->flush();

        return $this->redirectToRoute('app_container_list');
    }
}