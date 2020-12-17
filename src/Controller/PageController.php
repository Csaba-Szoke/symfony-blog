<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Service\PaginationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->em = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/", name="index")
     * @Route("/category/{cat}", name="index_search_cat")
     * @Route("/tag/{tag}", name="index_search_tag")
     */
    public function index(PaginationHelper $paginationHelper)
    {
        $response = $this->em->getRepository(Blog::class)->findAllPublicWithSearchAndRelations([
            'search' => $this->request->get('search'),
            'catSlug' => $this->request->attributes->get('cat'),
            'tagSlug' => $this->request->attributes->get('tag'),
        ]);
        $blogs = $paginationHelper->paginate($response['query'], $this->request->get('page', 1), 5);
        $categories = $this->em->getRepository(BlogCategory::class)->findAllWithBlogs();
        $tags = $this->em->getRepository(Tag::class)->findAllWithBlogs();

        return $this->render('index.html.twig', [
            'blogs' => $blogs,
            'categories' => $categories,
            'tags' => $tags,
            'searched' => $response['searched'],
        ]);
    }
}
