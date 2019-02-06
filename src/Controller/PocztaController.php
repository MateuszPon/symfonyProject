<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 */

namespace App\Controller;


use App\Form\SearchPPPackageType;
use App\Services\pocztaPolska;
use App\Services\WseAuthSoapHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/poczta")
 */

class PocztaController extends AbstractController
{
    /**
     * @Route("/", name="poczta_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $tracking = new pocztaPolska();
        $form = $this->createForm(SearchPPPackageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $danePrzesylki=$tracking->sprawdzPrzesylke($form->getData()['number']);
            if($danePrzesylki!= false){

                    return $this->render('poczta/poczta.html.twig', [
                        'checkNumber'=>true,
                        'form'=>$form->createView(),
                        'zdarzenia'=>($danePrzesylki),
                    ]);
            }
            else{
                $this->addFlash(
                  'notice',
                    'nie mozna znalezc przesylki o podanym numerze'
                );
            }
        }
        $checkNumber=false;
        return $this->render('poczta/poczta.html.twig', [
            'checkNumber'=>$checkNumber,
            'form'=>$form->createView(),
            'zdarzenia'=>null,
        ]);
    }
}