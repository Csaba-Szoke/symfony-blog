<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private $em;
    private $request;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @Route("/save-comment/{blog_id}", name="save_comment", methods={"POST"})
     */
    public function save($blog_id, BlogRepository $blogRepo, ValidatorInterface $validator)
    {
        $blog = $blogRepo->find($blog_id);

        $comment = new Comment();
        $comment->setContent($this->request->get('comment'));
        $comment->setBlog($blog);
        $comment->setUser($this->getUser());

        $errors = $validator->validate($comment);
        $errorsArray = false;

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorsArray .= $error->getMessage() . '<br>';
            }

            $this->addFlash('errors', $errorsArray);
        } else {
            $this->em->persist($comment);
            $this->em->flush();
        }

        return $this->redirect($this->generateUrl('show_blog', ['id' => $blog_id]));
    }

    /**
     * @Route("/delete-comment", name="delete_comment", methods={"POST"})
     */
    public function delete(CommentRepository $commentRepo)
    {
        $comment = $commentRepo->find($this->request->get('id'));
        $errors = '';

        if (!$comment) {
            $errors .= 'Comment not found.';
        }

        if (!$errors) {
            $this->em->remove($comment);
            $this->em->flush();
        }

        return new JsonResponse(['errors' => $errors]);
    }

    /**
     * @Route("/update-comment", name="update_comment", methods={"POST"})
     */
    public function update(CommentRepository $commentRepo)
    {
        $comment = $commentRepo->find($this->request->get('id'));
        $errors = '';

        if (!$comment) {
            $errors .= 'Comment not found.';

            return new JsonResponse(['errors' => $errors]);
        }

        $comment->setContent($this->request->get('content'));
        $this->em->flush();
        
        return new JsonResponse(['content' => $this->request->get('content')]);
    }
}
