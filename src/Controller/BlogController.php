<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Service\ItemTypeHelper;
use App\Service\PaginationHelper;
use App\Repository\BlogRepository;
use App\Service\SearchOptionsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    private $blogRepo;
    private $em;
    private $request;

    public function __construct(BlogRepository $blogRepo, EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->blogRepo = $blogRepo;
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/blog/{id}", name="show_blog")
     */
    public function show(Blog $blog)
    {
        // $blog = $this->blogRepo->find($id);

        // if (!$blog) {
        //     throw $this->createNotFoundException(
        //         'No blog found for id ' . $id
        //     );
        // }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blogs", name="list_blog")
     */
    public function list(PaginationHelper $paginationHelper, SearchOptionsHelper $searchOptionsHelper, ItemTypeHelper $itemTypeHelper)
    {
        $query = $this->em->getRepository(Blog::class)->findAllPublicWithAdvancedSearchAndRelations($this->request, $searchOptionsHelper);
        $blogs = $paginationHelper->paginate($query, $this->request->get('page', 1), $searchOptionsHelper->getValue('perPage', $this->request->get('perPage')));
        $categories = $this->em->getRepository(BlogCategory::class)->findAll();
        $tags = $this->em->getRepository(Tag::class)->findAll();
        $allowedOptions = $searchOptionsHelper->getOptions();
        $activeItemType = $itemTypeHelper->getActiveItemType($this->request, $searchOptionsHelper);
        $searched = $this->handleSearchedVariables($allowedOptions);

        return $this->render('blog/list.html.twig', [
            'blogs' => $blogs,
            'categories' => $categories,
            'tags' => $tags,
            'searched' => $searched,
            'allowedOptions' => $allowedOptions,
            'activeItemType' => $activeItemType,
        ]);
    }

    /**
     * @Route("/like-blog", methods={"POST"})
     */
    public function like()
    {
        $errors = "";

        if (!$this->getUser()) {
            $errors .= "You have to log in to like a post.";
        }

        $blogId = $this->request->get('id');
        $blog = $this->blogRepo->find($blogId);

        if (!$blog) {
            $errors .= "Blog doesn't exist.";
        }

        if (!$errors) {
            if ($this->getUser()->isBlogLiked($blogId)) {
                $this->getUser()->removeLike($blog);
            } else {
                $this->getUser()->addLike($blog);
            }

            $this->em->flush();
        }

        return new JsonResponse(['likes' => $blog->getLikes()->count(), 'errors' => $errors]);
    }

    private function handleSearchedVariables($allowedOptions)
    {
        $searched = $this->request->query->all();

        while (($key = array_search('', $searched)) !== false) {
            unset($searched[$key]);
        }

        foreach ($allowedOptions as $key => $value) {
            unset($searched[$key]);
        }

        unset($searched['page']);

        return $searched;
    }
}
