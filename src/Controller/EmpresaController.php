<?php

namespace App\Controller;

use App\Entity\Empresa;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use App\Form\EmpresaType;
use App\Repository\EmpresaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/empresa')]
class EmpresaController extends AbstractController
{
    #[Route('/', name: 'empresa_index', methods: ['GET'])]
    public function index(EmpresaRepository $empresaRepository, Request $request): Response
    {
        $empresas = $empresaRepository->findAll();
        /*$queryBuilder = $this->getDoctrine()->getRepository(Empresa::class)
            ->createQueryBuilder('e')
             ->select('*')
            ->getQuery();

        $list = $query->getResult();

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($list)
        );*/
        
        $adapter = new ArrayAdapter($empresas);
        $pagerfanta = new Pagerfanta($adapter);
        //$pagerfanta->setMaxPerPage(2);
        //$pagerfanta->getMaxPerPage();
        if ($request->get('page') !== null) {
            $pagerfanta->setCurrentPage($request->get('page'));
        }
        //dump($pagerfanta);
        return $this->render('empresa/index.html.twig', [
            //'empresas' => $empresas,
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/new', name: 'empresa_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($empresa);
            $entityManager->flush();

            return $this->redirectToRoute('empresa_index');
        }

        return $this->render('empresa/new.html.twig', [
            'empresa' => $empresa,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'empresa_show', methods: ['GET'])]
    public function show(Empresa $empresa): Response
    {
        return $this->render('empresa/show.html.twig', [
            'empresa' => $empresa,
        ]);
    }

    #[Route('/{id}/edit', name: 'empresa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Empresa $empresa): Response
    {
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('empresa_index');
        }

        return $this->render('empresa/edit.html.twig', [
            'empresa' => $empresa,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'empresa_delete', methods: ['POST'])]
    public function delete(Request $request, Empresa $empresa): Response
    {
        if ($this->isCsrfTokenValid('delete'.$empresa->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($empresa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('empresa_index');
    }
}
