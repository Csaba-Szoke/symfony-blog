<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\BlogFormType;
use App\Service\UploaderHelper;
use App\Service\PaginationHelper;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class BlogController extends AbstractController
{
    private $em;
    private $request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
       $this->em = $entityManager;
       $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/blogs", name="admin_list_blog")
     */
    public function list(PaginationHelper $paginationHelper)
    {
        $query = $this->findAllWithSearch($this->request->query->get('search'));
        $blogs = $paginationHelper->paginate($query, $this->request->query->getInt('page', 1), 5);

        return $this->render('admin/blog/list.html.twig', [
            'blogs' => $blogs
        ]);
    }

    /**
     * @Route("/blog/add", name="admin_add_blog", methods={"POST", "GET"})
     */
    public function add(UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(BlogFormType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $blog = new Blog();
            $uploadedFile = $form['imgFile']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadBlogImg($uploadedFile);
                $blog->setImg($newFilename);
            }
            
            if ($data['tags']->count() > 0) {
                foreach ($data['tags'] as $tag) {
                    $blog->addTag($tag);
                }
            }

            $blog->setTitle($data['title']);
            $blog->setSlug($data['slug']);
            $blog->setContent($data['content']);
            $blog->setPublic($data['public']);
            $blog->setCategory($data['category']);
            $blog->setUser($this->getUser());

            $this->em->persist($blog);
            $this->em->flush();

            return $this->redirect($this->generateUrl('admin_list_blog'));
        }

        return $this->render('admin/blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/{id}/edit", name="admin_edit_blog", methods={"POST", "GET"})
     */
    public function edit($id, BlogRepository $blogRepo, UploaderHelper $uploaderHelper)
    {
        $blog = $blogRepo->find($id);

        if (!$blog) {
            throw $this->createNotFoundException(
                'No blog found for id ' . $id
            );
        }

        $form = $this->createForm(BlogFormType::class, $blog);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imgFile']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadBlogImg($uploadedFile);
                $blog->setImg($newFilename);
            }

            $this->em->flush();

            return $this->redirect($this->generateUrl('admin_list_blog'));
        }

        return $this->render('admin/blog/edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blog/{id}/delete", name="admin_delete_blog", methods={"POST"})
     */
    public function delete($id, BlogRepository $blogRepo)
    {
        $blog = $blogRepo->find($id);

        if (!$blog) {
            throw $this->createNotFoundException(
                'No blog found for id ' . $id
            );
        }

        $this->em->remove($blog);
        $this->em->flush();

        return $this->redirect($this->generateUrl('admin_list_blog'));
    }

    private function findAllWithSearch($search)
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('b')
            ->from('App\Entity\Blog', 'b');

        if ($search) {
            $queryBuilder->where('b.title LIKE :search OR b.slug LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('b.id', 'DESC');

        return $queryBuilder->getQuery();
    }
}
