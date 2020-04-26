<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\Validate;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

/**
 * @Route("api", name="api_")
*/
class PostController extends AbstractController
{
    /**
     * @Route("/posts/{id}", name="show_post", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns post with specific id",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Post::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Returns if post not found",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Post::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The field used to select post"
     * )
     * @SWG\Tag(name="rewards")
    */
    public function showPost(SerializerInterface $serializer, $id)
    {
       $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

       if (empty($post)) {
           $response = [
                'code' => 1,
                'message' => 'post not found',
                'error' => null,
                'result' => null
           ];

           return new JsonResponse($response, Response::HTTP_NOT_FOUND);
       }

       $data = $serializer->serialize($post, 'json');

       $response = [
            'code' => 0,
            'message' => 'success',
            'error' => null,
            'result' => json_decode($data)
       ];

       return new JsonResponse($response, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/posts", name="create_posts", methods={"POST"}) 
     */
    public function createPost(Request $request, SerializerInterface $serializer, Validate $validate) {
        $data = $request->getContent();

        $post = $serializer->deserialize($data, 'App\Entity\Post', 'json');

        $response = $validate->validateRequest($post);

        if (!empty($response)) {
            return new JsonResponse($response, Response::HTTP_BAD_REQUEST);        
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        $response = [
            'code' => 0,
            'message' => 'Post created!',
            'error' => null,
            'result' => null
        ];

        return new JsonResponse($response, 200);
    }

    /**
     * @Route("/posts",name="list_posts", methods={"GET"})
     */

    public function listPost(SerializerInterface $serializer) {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        if (!count($posts)) {
            $response = [
                'code' => 1,
                'message' => 'No posts found!',
                'errors' => null,
                'result' => null
            ];

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $serializer->serialize($posts,'json');

        $response = [
            'code' => 0,
            'message' => 'success',
            'errors' => null,
            'result' => json_decode($data)
        ];    

        return new JsonResponse($response,200);
    }


    /**
     * @param Request $request
     * @param $id
     * @Route("/posts/{id}",name="update_post", methods={"PUT"})
     * @return JsonResponse
     */
    public function updatePost(Request $request, $id, SerializerInterface $serializer, Validate $validate) {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        if (empty($post)) {
            $response = [
                'code' => 1,
                'message' => 'Post Not found !',
                'errors' => null,
                'result' => null
            ];

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $body = $request->getContent();


        $data = $serializer->deserialize($body,'App\Entity\Post','json');

        $reponse = $validate->validateRequest($data);

        if (!empty($reponse)) {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }

        $post->setTitle($data->getTitle());
        $post->setDescritpion($data->getDescritpion());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        $response = [
            'code' => 0,
            'message' => 'Post updated!',
            'errors' => null,
            'result' => null
        ];

        return new JsonResponse($response,200);

    }

    /**
     * @Route("/posts/{id}",name="delete_post", methods={"DELETE"})
     */

    public function deletePost($id) {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        if (empty($post)) {
            $response=array(

                'code' => 1,
                'message' => 'post Not found !',
                'errors' => null,
                'result' => null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        
        $response = [
            'code' => 0,
            'message' => 'post deleted !',
            'errors' => null,
            'result' => null
        ];

        return new JsonResponse($response,200);
    }
}
