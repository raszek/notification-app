<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonForm;
use App\Form\PersonType;
use App\Service\Person\PersonEditorFactory;
use App\Service\Person\PersonService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonController extends Controller
{

    public function __construct(
        private readonly PersonService $personService,
        private readonly PersonEditorFactory $personEditorFactory
    ) {
    }

    #[Route('/', 'app_person_list')]
    public function list(): Response
    {
        $people = $this->personService->list();

        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/people/create', 'app_person_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(PersonType::class, new PersonForm());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $createdPerson = $this->personService->create($form->getData());

            $this->addFlash('success', 'Successfully created a person.');

            return $this->redirectToRoute('app_person_edit', [
                'id' => $createdPerson->getId(),
            ]);
        }

        return $this->render('person/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/people/edit/{id}', 'app_person_edit')]
    public function edit(Person $person, Request $request): Response
    {
        $personRecord = $this->personService->getRecord($person);

        $form = $this->createForm(PersonType::class, PersonForm::fromPersonRecord($personRecord));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $personEditor = $this->personEditorFactory->create($person);

            $personEditor->edit($form->getData());

            $this->addFlash('success', 'Successfully edited a person.');

            return $this->redirectToRoute('app_person_edit', [
                'id' => $person->getId()
            ]);
        }

        return $this->render('person/edit.html.twig', [
            'form' => $form,
            'person' => $person
        ]);
    }

    #[Route('/people/remove/{id}', 'app_person_remove', methods: ['POST'])]
    public function remove(Person $person): Response
    {
        $this->personService->remove($person);

        $this->addFlash('success', 'Successfully removed a person.');

        return $this->redirectToRoute('app_person_list');
    }

}
