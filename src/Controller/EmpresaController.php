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
use App\Entity\Sector;
use App\Form\SectorType;
use App\Repository\SectorRepository;

#[Route('/empresa')]
class EmpresaController extends AbstractController
{
    #[Route('/', name: 'empresa_index', methods: ['GET'])]
    public function index(EmpresaRepository $empresaRepository, SectorRepository $sectorRepository, Request $request): Response
    {
        $empresas = $empresaRepository->findAll(); 
        
        $adapter = new ArrayAdapter($empresas);
        $pagerfanta = new Pagerfanta($adapter);

        if ($request->get('page') !== null) {
            $pagerfanta->setCurrentPage($request->get('page'));
        }

        return $this->render('empresa/index.html.twig', [
            'pager' => $pagerfanta,
            'sectores' => $sectorRepository->findAll(),
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

    //#[Route('/{id}', name: 'empresa_show', methods: ['GET'])]
    /*public function show(Empresa $empresa): Response
    {
        return $this->render('empresa/show.html.twig', [
            'empresa' => $empresa,
        ]);
    }*/

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

    #[Route('/find', name: 'empresa_find', methods: ['GET'])]
    public function find(EmpresaRepository $empresaRepository, SectorRepository $sectorRepository, Request $request): Response
    {
        if ($request->get('empresa') !== null) {
            if ($request->get('empresa')['nombre'] == null) {
                $empresas = $empresaRepository->findBy(
                    ['sector' => $request->get('empresa')['sector']],
                );
            } elseif ($request->get('empresa')['sector'] == 0) {
                $empresas = $empresaRepository->findBy(
                    ['nombre' => $request->get('empresa')['nombre']],
                );
            } else {
                $empresas = $empresaRepository->findBy(
                    ['nombre' => $request->get('empresa')['nombre'],
                    'sector' => $request->get('empresa')['sector']],
                );
            }     
        } else {
            return $this->redirectToRoute('empresa_index');
        }

        $adapter = new ArrayAdapter($empresas);
        $pagerfanta = new Pagerfanta($adapter);

        if ($request->get('page') !== null) {
            $pagerfanta->setCurrentPage($request->get('page'));
        }

        return $this->render('empresa/index.html.twig', [
            'pager' => $pagerfanta,
            'sectores' => $sectorRepository->findAll(),
        ]);
    }
}
