<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessageType;
use App\Repository\MessagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    /**
     * Write new message
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/message/new', name:"app_new", methods: ['GET', 'POST'])]
    public function send(Request $request, EntityManagerInterface $manager): Response
    {
        $message = new Messages();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $message = $form->getData();
            $message->setSender($this->getUser());
            $manager->persist($message);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé'
            );

            return $this->redirectToRoute('app_message');
        }

        return $this->renderForm('pages/message/new.html.twig',[ 
            'form' => $form
        ]);
    }

    
    /**
     * Change status read message
     *
     * @param Messages $message
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/message/read/{id}', name:"app_read", methods:['GET'])]
    public function read(Messages $message, EntityManagerInterface $manager): Response {

        $message->setIsRead(true);
        $manager->persist($message);
        $manager->flush();

        return $this->render('pages/message/message.html.twig', [
            'message' => $message
        ]);
    }


    
    /**
     * Move message in the trashbin
     *
     * @param Messages $message
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/message/delete/{id}', name:"app_delete", methods:['GET'])]
    public function delete(Messages $message, EntityManagerInterface $manager): Response {
      
        $message->setTrashbin(true);
        $manager->persist($message);
        $manager->flush();

        $this->addFlash('success', 'Votre message a été mit dans la corbeille');

        return $this->redirectToRoute('app_received');
    }

    #[Route('/message/received', name:"app_received", methods: ['GET'])]
    public function received(MessagesRepository $messagesRepository): Response
    {
        // $messages = $messagesRepository->findBy(['recipient' => $this->getUser()]);
        return $this->render('pages/message/received.html.twig', [
            // 'messages' => $messages
        ]);
    }


    /**
     * Display sent message
     *
     * @return Response
     */
    #[Route('/message/sent', name:"app_sent", methods: ['GET'])]
    public function sent(): Response
    {

        return $this->render('pages/message/sent.html.twig');
    }



    /**
     * Display message in the trashbin
     *
     * @return void
     */
    #[Route('/message/trash', name:"app_trash", methods: ['GET'])]
    public function trashInterface() {
        return $this->render('pages/message/trashbin.html.twig');
    }


    #[Route('/message/trash/all', name:"app_trash_all_delete", methods: ['GET'])]
    public function deleteAll(MessagesRepository $repo, EntityManagerInterface $manager): Response
    {
        
        $messages = $repo->findBy(['trashbin' => true]);

        foreach ($messages as $message) {
            $manager->remove($message);
        }

        $manager->flush();

        $this->addFlash('success', 'La corbeille a bien été vidée');

        return $this->redirectToRoute('app_trash'); 
    }

    
    /**
     * Delete message
     *
     * @param Messages $message
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/message/trash/{id}', name:"app_trash_delete", methods: ['GET'])]
    public function trash(Messages $message, EntityManagerInterface $manager): Response
    {
        $manager->remove($message);
        $manager->flush();

        $this->addFlash('success', 'Votre message a été supprimé');

        return $this->redirectToRoute('app_trash');
    }

}
