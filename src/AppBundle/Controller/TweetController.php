<?php

namespace AppBundle\Controller;

use AppBundle\Form\TweetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Tweet;

class TweetController extends Controller
{
    /**
     * @Route("/", name="app_tweet_list", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response)
     */
    public function listAction(Request $request)
    {
        $tweets = $this->getDoctrine()->getRepository(Tweet::class)->getLastTweets(
            $this->getParameter('app.tweet.nb_last',10) //valeur par défaut
        );
        return $this->render(':tweet:list.html.twig', [
            'tweets'=> $tweets
        ]);
    }


    /**
     * @Route("/tweet/new", name="app_tweet_new", methods={"GET", "POST"})
     *
     *
     */

    public function newAction(Request $request)
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $tweeter = $this->getDoctrine()->getManager();

                $tweeter->persist($tweet);
                $tweeter->flush();


                return $this->redirectToRoute('app_tweet_view', [
                    'id' => $tweet->getId(),
                ]);


            }

        }

                return $this->render(':tweet:new.html.twig', [
                    'form' => $form->createView()

                ]);




    }

    /**
     * @Route("/tweet/{id}", name="app_tweet_view", methods={"GET"})
     *
     *
     */
   public function viewAction($id)
    {

            $tweet = $this->getDoctrine()->getRepository(Tweet::class)->getTweet($id);
            if($tweet != NULL) {


                return $this->render(':tweet:view.html.twig', [
                    'tweet' => $tweet,
                ]);
            }

            else{
                throw new NotFoundHttpException("Aucun tweet trouvé");
            }



    }



}
