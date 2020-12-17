<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Service\PaginationHelper;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class TagController extends AbstractController
{
    private $em;
    private $request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
       $this->em = $entityManager;
       $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/tags", name="admin_list_tag")
     */
    public function list(PaginationHelper $paginationHelper)
    {
        $query = $this->findAllWithSearch($this->request->query->get('search'));
        $tags = $paginationHelper->paginate($query, $this->request->query->getInt('page', 1), 5);

        return $this->render('admin/tag/list.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/tags/add", name="admin_add_tag", methods={"POST", "GET"})
     */
    public function add()
    {
        $form = $this->createForm(TagFormType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $tag = new Tag();
            $tag->setName($data['name']);
            $tag->setSlug($data['slug']);

            $this->em->persist($tag);
            $this->em->flush();

            return $this->redirect($this->generateUrl('admin_list_tag'));
        }

        return $this->render('admin/tag/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tag/{id}/edit", name="admin_edit_tag", methods={"POST", "GET"})
     */
    public function edit($id, TagRepository $tagRepo)
    {
        $tag = $tagRepo->find($id);

        if (!$tagRepo) {
            throw $this->createNotFoundException(
                'No tag found for id ' . $id
            );
        }

        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirect($this->generateUrl('admin_list_tag'));
        }

        return $this->render('admin/tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag
        ]);
    }

    /**
     * @Route("/tag/{id}/delete", name="admin_delete_tag", methods={"POST"})
     */
    public function delete($id, TagRepository $tagRepo)
    {
        $tag = $tagRepo->find($id);

        if (!$tag) {
            throw $this->createNotFoundException(
                'No tag found for id ' . $id
            );
        }

        $this->em->remove($tag);
        $this->em->flush();

        return $this->redirect($this->generateUrl('admin_list_tag'));
    }

    private function findAllWithSearch($search)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('t')
            ->from('App\Entity\Tag', 't');

        if ($search) {
            $queryBuilder->where('t.name LIKE :search OR t.slug LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('t.id', 'DESC');

        return $queryBuilder->getQuery();
    }
}
