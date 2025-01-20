<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonController extends Controller
{

    public function __construct(
        private readonly PersonRepository $personRepository
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

}
