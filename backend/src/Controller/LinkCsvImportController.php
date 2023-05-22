<?php

namespace App\Controller;

use App\Request\CreateRaceRequest;
use App\Request\UploadCsvRequest;
use App\Service\UrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkCsvImportController extends AbstractController
{
    public function __construct(private UrlService $urlService)
    {
    }

    #[Route('/link/csv/import', name: 'app_link_csv_import')]
    public function index(Request $request): Response
    {
        $csvFile = new UploadCsvRequest();

        $form = $this->createFormBuilder($csvFile)
            ->add('csvFile', FileType::class, array('label' => 'csvFile (csv)'))
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $csvFile->getCsvFile();

            $csvFile->setCsvFile($file);

            $count = $this->urlService->handleData($csvFile);
            $response = "csv is successfully uploaded.\n number of saved links:    " . $count;

            return $this->render('link_csv_import/link-response.html.twig', ['response' => $response]);
        } else {
            return $this->render('link_csv_import/index.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }
}
