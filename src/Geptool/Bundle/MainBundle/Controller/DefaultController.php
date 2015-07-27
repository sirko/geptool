<?php

namespace Geptool\Bundle\MainBundle\Controller;

use Doctrine\Common\Collections;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Geptool\Bundle\MainBundle\Entity\JiraProject;
use Geptool\Bundle\MainBundle\Entity\JiraUser;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Default controller class
 */
class DefaultController extends Controller
{
  /**
   * Default action
   * @return Symfony\Component\HttpFoundation\Response response
   */
    public function indexAction()
    {
        return $this->render('GeptoolMainBundle:Default:index.html.twig');
    }
}
