<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditorController extends AbstractController
{
    //Function to show the list of every editor in the dataBase 
    #[Route('/editor', name: 'app_editor')]
    public function index(EditorRepository $editorRepository): Response
    {

        $editors = $editorRepository->findBy([], ['name' => 'ASC']);
        return $this->render('editor/index.html.twig', [
            'editors' => $editors,
        ]);
    }

    //add a editor in the data base
    #[Route('/editor/neweditor', name: 'new_editor')]
    //modify a editor in the data base
    #[Route('/editor/{id}/editeditor', name: 'edit_editor')]
    public function newEditEditor(Editor $editor = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$editor) {
            $editor = new Editor();
            $edit = "";
        }else {
            $edit = $editor;
        }

        $form = $this->createForm(EditorType::class, $editor);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $editor = $form->getData();

            // tell Doctrine you want to (eventually) save the editor (no queries yet)
            $entityManager->persist($editor);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_editor');
        }

        return $this->render('editor/editorForm.html.twig', [
            'form' => $form,
            'edit' => $edit
        ]);

    }

    //function to delete a editor
    #[Route('/editor/{id}/deleteEditor', name: 'delete_editor')]
    public function editorDelete(Editor $editor, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($editor);
        $entityManager->flush();

        return $this->redirectToRoute('app_editor');
    }

    //function to show the details of an editor (games, country...)
    #[Route('/editor/{id}', name: 'details_editor')]
    public function editorDetails(Editor $editor): Response
    {
        return $this->render('editor/editorDetails.html.twig', [
            'editor' => $editor,
        ]);
    }

}
