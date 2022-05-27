<?php

namespace App\Controller;

use App\Entity\Field;
use App\Form\FieldType;
use App\Repository\CategoryRepository;
use App\Repository\FieldRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function json_encode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use function var_dump;



class FieldController extends OverrideController
{

    /**
     * @Route("/terrain/1_2", name="terrain_show12")
     */
    public function terrain_show12(FieldRepository $fieldRepository): Response
    {


        return $this->render("terrains/show12.html.twig",["terrain1"=>$fieldRepository->find(1),"terrain2"=>$fieldRepository->find(2)]);

    }

    /**
     * @Route("/terrain/{id}", name="terrain_show")
     */
    public function terrain_show( string $id, FieldRepository $fieldRepository): Response
    {

        $field = $fieldRepository->find($id);
        return $this->render("terrains/show.html.twig",["matchs"=>$field->getMeets(),"terrain"=>$field]);

    }

    /**
     * @Route("/admin/terrain", name="admin_terrain")
     */
    public function admin_terrain(CategoryRepository $categoryRepository, FieldRepository $fieldRepository): Response
    {
        return $this->render("admin/terrains/index.html.twig",["categories" =>$categoryRepository->findAll() ,"terrains"=>$fieldRepository->findAll()]);

    }

    /**
     * @Route("/admin/terrain/add/", name="admin_terrain_add")
     */
    public function admin_terrain_add( Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, FieldRepository $fieldRepository): Response
    {
       $name = $request->get("name");
       $field = new Field();
       $field->setName($name);
       $entityManager->persist($field);
       $entityManager->flush();

        return $this->redirectToRoute("admin_terrain");
    }

    /**
     * @Route("/admin/terrain/delete/{id}", name="admin_terrain_delete")
     */
    public function admin_terrain_delete(string $id,CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, FieldRepository $fieldRepository): Response
    {
        $field = $fieldRepository->find($id);
        if($field)
        {
            $entityManager->remove($field);
            $entityManager->flush();
        }
        return $this->render("admin/terrains/index.html.twig",["categories" =>$categoryRepository->findAll() ,"terrains"=>$fieldRepository->findAll()]);

    }

    /**
     * @Route("/admin/terrain/{id}", name="app_admin_update_terrain")
     */
    public function app_admin_update_terrain(string $id, Request $request, EntityManagerInterface $entityManager, FieldRepository $fieldRepository, CategoryRepository $categoryRepository): Response
    {
        $field = $fieldRepository->find($id);
        $form = $this->createForm(FieldType::class, $field);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $field = $form->getData();
            $entityManager->persist($field);
            $entityManager->flush();
            $this->addFlash("success","Terrain modifiée avec succès");
            return $this->redirectToRoute("admin_terrain");
        }

        return $this->render('admin/terrains/update.html.twig', [
            'form' => $form->createView(),
            'team' => $field,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/terrains", name="terrains_index")
     */
    public function terrains_index(FieldRepository $fieldRepository): Response
    {

        return $this->render("terrains/index.html.twig",["fields"=>$fieldRepository->findAll()]);

    }

    /**
     * @Route("/api/fields", name="fields_api")
     */
    public function index(FieldRepository $fieldRepository, SerializerInterface $serializer): Response
    {
       return $this->send($serializer->serialize($fieldRepository->findAll(),'json',['groups' => ['field']]));
    }
}
