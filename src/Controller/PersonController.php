<?php

namespace App\Controller;

use App\Form\PersonForm;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Service\PersonService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonController extends Controller
{

    public function __construct(
        private readonly PersonRepository $personRepository,
        private readonly PersonService $personService
    ) {
    }

    #[Route('/', 'app_person_list')]
    public function list(): Response
    {
        $people = $this->personRepository->findAll();

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
            $this->personService->create($form->getData());

            return $this->redirectToRoute('app_person_list');
        }

        return $this->render('person/create.html.twig', [
            'form' => $form
        ]);
    }

}
