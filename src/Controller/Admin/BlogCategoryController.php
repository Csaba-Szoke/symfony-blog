<?php

namespace App\Controller\Admin;

use App\Entity\BlogCategory;
use App\Service\PaginationHelper;
use App\Form\BlogCategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BlogCategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class BlogCategoryController extends AbstractController
{
    private $em;
    private $request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
       $this->em = $entityManager;
       $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/blog-category", name="admin_list_blog_cat")
     */
    public function list(PaginationHelper $paginationHelper)
    {
        $query = $this->findAllWithSearch($this->request->query->get('search'));
        $blogCats = $paginationHelper->paginate($query, $this->request->query->getInt('page', 1), 5);

        return $this->render('admin/blog-category/list.html.twig', [
            'blogCats' => $blogCats
        ]);
    }

    /**
     * @Route("/blog-category/add", name="admin_add_blog_cat", methods={"POST", "GET"})
     */
    public function add()
    {
        $form = $this->createForm(BlogCategoryFormType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $blogCat = new BlogCategory();
            $blogCat->setTitle($data['title']);
            $blogCat->setSlug($data['slug']);

            $this->em->persist($blogCat);
            $this->em->flush();

            return $this->redirect($this->generateUrl('admin_list_blog_cat'));
        }

        return $this->render('admin/blog-category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog-category/{id}/edit", name="admin_edit_blog_cat", methods={"POST", "GET"})
     */
    public function edit($id, BlogCategoryRepository $blogCatRepo)
    {
        $blogCat = $blogCatRepo->find($id);

        if (!$blogCat) {
            throw $this->createNotFoundException(
                'No blog category found for id ' . $id
            );
        }

        $form = $this->createForm(BlogCategoryFormType::class, $blogCat);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirect($this->generateUrl('admin_list_blog_cat'));
        }

        return $this->render('admin/blog-category/edit.html.twig', [
            'form' => $form->createView(),
            'blogCat' => $blogCat
        ]);
    }

    /**
     * @Route("/blog-category/{id}/delete", name="admin_delete_blog_cat", methods={"POST"})
     */
    public function delete($id, BlogCategoryRepository $blogCatRepo)
    {
        $blogCat = $blogCatRepo->find($id);

        if (!$blogCat) {
            throw $this->createNotFoundException(
                'No blog category found for id ' . $id
            );
        }

        $this->em->remove($blogCat);
        $this->em->flush();

        return $this->redirect($this->generateUrl('admin_list_blog_cat'));
    }

    private function findAllWithSearch($search)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('b_c')
            ->from('App\Entity\BlogCategory', 'b_c');

        if ($search) {
            $queryBuilder->where('b_c.title LIKE :search OR b_c.slug LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('b_c.id', 'DESC');

        return $queryBuilder->getQuery();
    }
}
