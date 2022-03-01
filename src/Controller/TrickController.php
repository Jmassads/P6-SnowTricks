<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick", name="trick.")
 */
class TrickController extends AbstractController
{

    #[Route('/', name: 'all')]
    public function index(EntityManagerInterface $em, TrickRepository $trickRepository): Response
    {
        $user = $this->getUser();
//        $tricks = $em
//            ->getRepository('App:Trick')
//            ->findBy(array('author' => $user), array()
//            );
        $tricks = $trickRepository ->findBy(array('author' => $user), array()
        );
        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em)
    {
        $trick = new trick();

        //FORM
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $trick->setAuthor($this->getUser());
            $trick->setCreatedAt(new \DateTimeImmutable());

            $image = $request->files->get('trick')['attachment'];
            if ($image) {
                $filename = md5(uniqid()). '.' .$image->guessClientExtension();
            }
            $image->move(
                $this->getParameter('images_folder'), $filename
            );
            $trick->setImage($filename);

            $em->persist($trick);
            $em->flush();
            $this->addFlash(
                'success',
                "La figure <strong>{$trick->getTitle()}</strong> a bien été enregistrée"
            );
            return $this->redirect($this->generateUrl('trick.all'));
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('trick/edit.html.twig', [

        ]);
    }
}
