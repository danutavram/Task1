<?php


namespace App\Controller;

use App\Document\Firma;
use App\Form\Type\FormTypeFirma;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Tools\Pagination\Paginator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class FirmaController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function mainPage(): Response
    {
        return $this->render('firma/main_page.html.twig');
    }
    /**
     * @Route("/firme", name="firmaLista", methods={"GET"})
     */
    public function listaFirme(Request $request, DocumentManager $dm, PaginatorInterface $paginator): Response
    {
        $session = $request->getSession();
        $index = $session->get("firm_index", 1);
        $firmaRepository = $dm->getRepository(Firma::class);
        $firmaLista = $firmaRepository->findAll();

        $pagination = $paginator->paginate(
            $firmaLista,
            $request->query->getInt('page', 1),
            10
        );

        $session->set('firm_index', $index + 1);

        return $this->render('firma/lista.html.twig', [
            'firmaLista' => $firmaLista,
            'pagination' => $pagination,
            'index' => $index,
        ]);
    }
    /**
     * @Route("/firma/adauga", name="firma_adauga", methods={"GET", "POST"})
     */
    public function adaugaFirma(Request $request, DocumentManager $dm): Response
    {
        $form = $this->createForm(FormTypeFirma::class, new Firma());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firma = $form->getData();

            if ($firma->getLogoFile()) {
                $firma->setLogo($firma->getLogoFile()->getClientOriginalName());
                $dm->persist($firma);
            }

            $dm->persist($firma);
            $dm->flush();

            return $this->redirectToRoute('firmaLista');
        }

        return $this->render('firma/adauga.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/firma/{id}", name="firma_vezi", methods={"GET"})
     */
    public function veziFirma(Firma $firma, DocumentManager $dm): Response
    {
        $views = $firma->getViews();
        $firma->setViews($views + 1);
        $dm->persist($firma);
        $dm->flush();

        return $this->render('firma/vezi.html.twig', ['firma' => $firma]);
    }
    /**
     * @Route("/firma/editare/{id}", name="firma_editare", methods={"GET", "POST"})
     */
    public function editareFirma(Firma $firma, Request $request, DocumentManager $dm): Response
    {
        $form = $this->createForm(FormTypeFirma::class, $firma);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->flush();
            $dm->persist($firma);

            return $this->redirectToRoute('firmaLista');
        }
        return $this->render('firma/editare.html.twig', [
            'form' => $form->createView(),
            'firma' => $firma,
        ]);
    }


    public function stergeFirma(Firma $firma, DocumentManager $dm, Request $request): Response
    {
        $form = $this->createForm(FormTypeFirma::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->remove($firma);
            $dm->flush();

            return $this->redirectToRoute('firmaLista');
        }
        return $this->render('firma/sterge.html.twig', [
            'form' => $form->createView(),
            'firma' => $firma,
        ]);
    }

    /**
     * @Route("/firma/sterge-confirma/{id}", name="firmaStergeConfirma", methods={"GET"})
     */
    public function stergeFirmaConfirma(Firma $firma, DocumentManager $dm): Response
    {
        $dm->remove($firma);
        $dm->flush();

        return $this->redirectToRoute('firmaLista');
    }

    /**
     * @Route("/firme/top-views", name="firme_top_views", methods={"GET"})
     */
    public function topViewedFirms(DocumentManager $dm): Response
    {
        $firmaRepository = $dm->getRepository(Firma::class);

        $qb = $firmaRepository->createQueryBuilder('f')
            ->sort('views', 'desc')
            ->limit(10);

        $topFirme = $qb->getQuery()->execute();

        $index = 1;
        foreach ($topFirme as $firma) {
            if ($firma->getViews() === null) {
                $firma->setViews(0);
            }

            $dm->persist($firma);
            $firma->setIndex($index);
            $index++;
        }

        $dm->flush();

        return $this->render('firma/top_views.html.twig', [
            'topFirme' => $topFirme,
        ]);
    }

    /**
     * @Route("/firma/{id}/increment-view", name="increment_view", methods={"GET"})
     */
    public function incrementView(Firma $firma, DocumentManager $dm): Response
    {
        $views = $firma->getViews();
        $firma->setViews($views + 1);
        $dm->persist($firma);
        $dm->flush();

        return $this->redirectToRoute('firma_vezi', ['id' => $firma->getId()]);
    }
}
